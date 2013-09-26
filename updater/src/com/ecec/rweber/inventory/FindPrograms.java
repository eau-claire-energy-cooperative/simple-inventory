package com.ecec.rweber.inventory;

import java.util.ArrayList;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.jdom.Element;
import org.json.simple.JSONObject;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.exception.InvalidDatasourceException;
import com.ecec.rweber.conductor.framework.datasources.sql.SQLDatasource;
import com.ecec.rweber.inventory.api.ApiManager;
import com.ecec.rweber.inventory.utils.Database;
import com.ecec.rweber.inventory.utils.GetDBSettings;
import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.utils.SettingsReader;

public class FindPrograms extends Car{
	private Integer computerId = null;
	private ApiManager api = null;
	
	public FindPrograms(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
		this.settingsGrabber = new GetDBSettings();
		
		//create the api manager
		api = ApiManager.getInstance(parameters.get("inventory_url"));
		
		//find the computer ID in the database
		String computerName = PCInfo.getComputerName();
		
		if(computerName != null)
		{
			//get this computer's ID based on the name
			JSONObject queryResults = api.computer_exists(computerName);
			
			if(queryResults != null && !queryResults.get("type").equals(ApiManager.RESPONSE_ERROR))
			{
				JSONObject computerO = (JSONObject)queryResults.get("result");
				computerId = new Integer(computerO.get("id").toString());
			}
			
		}
		
	}

	@Override
	protected void cleanup() {
		
	}

	@Override
	protected void runImp(Helper arg0) {
		List<PCProgram> allPrograms = new ArrayList<PCProgram>();
		
		try{
			
			List<Element> wmi = jWMI.getWMIValues("select * from Win32_Product", "Name");
			
			Element temp = null;
			for(int i = 0; i < wmi.size(); i ++)
			{
				temp = wmi.get(i);
				allPrograms.add(new PCProgram(temp.getChildText("Name"),temp.getChildText("Name")));
					
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		if(computerId != null)
		{
			logInfo("Found " + allPrograms.size() + " programs for computer " + PCInfo.getComputerName());
			
			//reuse these parameters, just reset the program name
			Map<String,String> params = new HashMap<String,String>();
			params.put("id",computerId.toString());
			
			//clear out the current programs list
			api.programs(ApiManager.PROGRAMS_CLEAR, params);
			
			PCProgram p = null;
			for(int i = 0; i < allPrograms.size(); i ++)
			{
				//add the program for this computer
				p = allPrograms.get(i);
				params.put("program", p.name);
				api.programs(ApiManager.PROGRAMS_ADD, params);
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	
	}

	public class PCProgram {
		public String name; 
		public String version;
		
		public PCProgram(String name, String version)
		{
			this.name = name;
			this.version = version;
		}
		
		public String toString(){
			return name + ": " + version;
		}
	}
}
