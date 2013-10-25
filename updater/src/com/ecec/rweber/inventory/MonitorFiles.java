package com.ecec.rweber.inventory;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.jdom.Element;
import org.json.simple.JSONObject;

import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;
import com.ecec.rweber.inventory.utils.PCInfo;

public class MonitorFiles extends Car{
	private ApiManager api = null;
	private List<File> monitorFiles = null;
	private int daysToLive = 2;
	
	public MonitorFiles(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
	
		//create the api manager
		api = ApiManager.getInstance(parameters.get("inventory_url"));
		
		//create the list of files to monitor
		monitorFiles = new ArrayList<File>();
		
		String[] fileString = this.parameters.get("files").split(",");
		
		for(int count = 0; count < fileString.length; count ++)
		{
			File f = new File(fileString[count]);
			
			if(f.exists() && !f.isDirectory())
			{
				monitorFiles.add(f);
			}
			else
			{
				this.logError("File " + fileString[count] + " cannot be monitored");
			}
		}
		
		//set the number of days to live
		if(this.parameters.get("days_to_live") != null)
		{
			daysToLive = Integer.parseInt(this.parameters.get("days_to_live"));
		}
		
	}

	@Override
	protected void cleanup() {
		
		
	}

	@Override
	protected void runImp(Helper arg0) {
		//1000 milliseconds * 60 seconds * 60 minutes * 24 hours
		long timeToLive = System.currentTimeMillis() - (1000 * 60 * 60 * 24 * daysToLive);
		Integer computerId = null;
		
		//create a default map for the api
		Map<String,String> jsonParams = new HashMap<String,String>();
		
		//get this computer's ID based on the name
		JSONObject queryResults = api.computer_exists(PCInfo.getComputerName());
		
		if(queryResults != null && !queryResults.get("type").equals(ApiManager.RESPONSE_ERROR))
		{
			JSONObject computerO = (JSONObject)queryResults.get("result");
			computerId = new Integer(computerO.get("id").toString());
		}
		
		
		if(computerId != null)
		{
			jsonParams.put("id", computerId.toString());
			jsonParams.put("type","file");
			
			//go through and check the file list
			Iterator<File> fileIter = monitorFiles.iterator();
			File f = null;
			
			while(fileIter.hasNext())
			{
				f = fileIter.next();
				
				if(f.lastModified() < timeToLive)
				{
					//send a notification for this file
					this.logInfo("File " + f.getAbsolutePath() + " is expired for computer " + computerId);
					
					jsonParams.put("alarm", "file_" + f.getName());
					jsonParams.put("note",f.getAbsolutePath());
					
					api.alarm(ApiManager.ALARM_TRIGGER, jsonParams);
				}
				else
				{
					jsonParams.put("alarm", "file_" + f.getName());
					api.alarm(ApiManager.ALARM_REMOVE, jsonParams);
				}
			}
		}
		else
		{
			this.logError("This computer is not in the inventory system");
		}
		
	}

}
