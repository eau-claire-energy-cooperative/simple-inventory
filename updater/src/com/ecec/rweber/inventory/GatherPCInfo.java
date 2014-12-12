package com.ecec.rweber.inventory;

import java.sql.Timestamp;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.cmd.Shell;
import org.jdom.Element;
import org.json.simple.JSONObject;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;
import com.ecec.rweber.inventory.commands.*;
import com.ecec.rweber.inventory.utils.Disk;
import com.ecec.rweber.inventory.utils.GetDBSettings;
import com.ecec.rweber.inventory.utils.PCInfo;

public class GatherPCInfo extends Car{
	private Sigar sigar;
	private ApiManager api = null;
	
	public GatherPCInfo(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
		this.settingsGrabber = new GetDBSettings();
		
		//create Sigar for PC info
		sigar = new Shell().getSigar();
		
		//create api
		api = ApiManager.getInstance(parameters.get("inventory_url"));
	}

	@Override
	protected void cleanup() {
		
	}

	@Override
	protected void runImp(Helper arg0) {
		
		PCInfo results = runCommands();
		sendResults(results);
	}

	private PCInfo runCommands(){
		PCInfo results = new PCInfo();
		SigarCommand c = null;
		
		//get the total memory
		c = new MemoryCommand();
		results = c.runCommand(sigar, results);
		
		//processor info
		c = new ProcessorCommand();
		results = c.runCommand(sigar, results);
		
		//network info
		c = new NetworkInfoCommand();
		results = c.runCommand(sigar, results);
		
		//current user
		c = new CurrentUserCommand();
		results = c.runCommand(sigar, results);
		
		//operating system
		c = new OSWindowsCommand();
		results = c.runCommand(sigar, results);
		
		//disk usage
		c = new DiskUsageCommand();
		results = c.runCommand(sigar, results);
		
		c = new ModelInfoWindowsCommand();
		results = c.runCommand(sigar, results);
		
		c = new MonitorsCommand();
		results = c.runCommand(sigar, results);
		
		c = new LastBootWindowsCommand();
		results = c.runCommand(sigar, results);
		
		results.addField("ComputerName", PCInfo.getComputerName());
		
		return results;
	}
	
	private void sendResults(PCInfo info){
		//get this computer's ID based on the name
		JSONObject queryResults = api.computer_exists(info.get("ComputerName"));
				
		Integer compId = null;
		
		if(queryResults != null && !queryResults.get("type").equals(ApiManager.RESPONSE_ERROR))
		{
			JSONObject computerO = (JSONObject)queryResults.get("result");
			compId = new Integer(computerO.get("id").toString());
			
			//add some fields for the database
			info.addField("id", compId.toString());
			info.addField("LastUpdated",new Timestamp(System.currentTimeMillis()).toString());
			
			//update the computer info in the database
			api.inventory(ApiManager.INVENTORY_UPDATE, info);
			
			//update the disk information
			Iterator<Disk> disks = info.getDisks().iterator();
			Disk aDisk = null;
			
			while(disks.hasNext())
			{
				aDisk = disks.next();
				aDisk.put("comp_id", info.get("id"));
				api.disk(ApiManager.DISK_UPDATE, aDisk);
			}
			
			logInfo("Updating computer " + info.get("ComputerName"));
		}
		else if(queryResults != null)
		{
			logError(info.get("ComputerName") + " is not in the Inventory System");
			
			//check if we are allowed to add this computer
			if(this.shouldAddComputer(info))
			{
				//add this computer to the inventory
				if(this.addComputer(info))
				{
					//we actually performed the add operation, try and do this again
					this.sendResults(info);
				}
			}
		}
		
	}
	
	private boolean shouldAddComputer(PCInfo info){
		boolean result = true;
		
		
		if(this.db_settings.containsKey("computer_ignore_list"))
		{
			String ignoreList = this.db_settings.get("computer_ignore_list").toLowerCase();
			String compName = info.get("ComputerName").toLowerCase();
			
			//set back to false if on ignore list, else do nothing
			if(ignoreList.contains(compName))
			{
				logError(compName + " is on ignore list, not sending add request");
				result = false;
			}
		}
		
		return result;
	}
	
	private boolean addComputer(PCInfo info){
		boolean result = false;	//by default assume we did not add the computer
		String subject = "";
		String message = "";

		if(this.db_settings.get("computer_auto_add").equals("true"))
		{
			//get the default location
			JSONObject defaultLocation = api.location(ApiManager.LOCATION_DEFAULT);
			
			if(defaultLocation != null && defaultLocation.get("type").equals(ApiManager.RESPONSE_SUCCESS))
			{
				//automatically add the computer
				Map<String,String> params = new HashMap<String,String>();
				params.put("ComputerName",info.get("ComputerName"));
				JSONObject addResult = api.inventory(ApiManager.INVENTORY_ADD, params);
				
				if(addResult != null && addResult.get("type").equals(ApiManager.RESPONSE_SUCCESS))
				{
					String computerUrl = parameters.get("inventory_url") + "inventory/moreInfo/" + ((JSONObject)addResult.get("result")).get("id");
							
					//notify the admins
					subject = "Computer Added";
					message = "Computer <b>" + info.get("ComputerName") + "</b> has been automatically added to the inventory system. Details are below: <br><br>" + 
						"Model: " + info.get("Model") + "<br>" + 
						"Serial Number: " + info.get("SerialNumber") + "<br>" + 
						"Current User: " + info.get("CurrentUser") + "<br>" + 
						"Computer Location: " + ((JSONObject)defaultLocation.get("result")).get("location") + "<br><br>" + 
						"<a href=\"" + computerUrl + "\">" + computerUrl + "</a>";
		
					
					logInfo("Computer " + info.get("ComputerName") + " added to database");
					result = true;
				}
			}
		}
		else
		{
			logInfo("Sending add request for computer " + info.get("ComputerName"));
	
			//create the body of the message
			subject = "Computer Add Request";
			message = "Computer <b>" + info.get("ComputerName") + "</b> is requesting to be added to the inventory. Details are below: <br><br>" +
			    "Model: " + info.get("Model") + "<br>" + 
				"Serial Number: " + info.get("SerialNumber") + "<br>" + 
				"Current User: " + info.get("CurrentUser");
						
		}
		
		api.send_email(subject, message);
		
		return result;
	}
}
