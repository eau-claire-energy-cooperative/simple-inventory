package com.ecec.rweber.inventory;

import java.util.ArrayList;


import java.util.HashMap;
import java.util.List;
import java.util.Map;
import org.jdom.Element;
import org.json.simple.JSONObject;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;
import com.ecec.rweber.inventory.utils.GetDBSettings;
import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.inventory.utils.WinRegistry;

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
		
		if(computerId != null)
		{
			try{
				
				//first get the 32 bit keys
				allPrograms.addAll(this.findPrograms(WinRegistry.readStringSubKeys(WinRegistry.HKEY_LOCAL_MACHINE, "SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall"),"SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall\\"));

				//try and get the 64 bit software
				allPrograms.addAll(this.findPrograms(WinRegistry.readStringSubKeys(WinRegistry.HKEY_LOCAL_MACHINE,"SOFTWARE\\Wow6432Node\\Microsoft\\Windows\\CurrentVersion\\Uninstall"),"SOFTWARE\\Wow6432Node\\Microsoft\\Windows\\CurrentVersion\\Uninstall"));				
				
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
			
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
				params.put("version",p.version);
				api.programs(ApiManager.PROGRAMS_ADD, params);
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	
	}

	public List<PCProgram> findPrograms(List<String> regList, String regKey){
		List<PCProgram> result = new ArrayList<PCProgram>();
		
		try{
			for(String guid : regList)
			{
				String displayName = WinRegistry.readString(WinRegistry.HKEY_LOCAL_MACHINE, regKey + guid, "DisplayName");
				String displayVersion = WinRegistry.readString(WinRegistry.HKEY_LOCAL_MACHINE, regKey + guid, "DisplayVersion");
				
				if(displayVersion == null)
				{
					displayVersion = "?";
				}
				
				if(displayName != null)
				{
					PCProgram newProg = new PCProgram(displayName,displayVersion);
					
					if(!result.contains(newProg))
					{
						result.add(newProg);
					}
				}
			}
		}
		catch(Exception e)
		{
			
		}
		
		return result;
	}
	
	public class PCProgram {
		public String name; 
		public String version;
		
		public PCProgram(String name, String version)
		{
			this.name = name.trim();
			this.version = version.trim();
		}
		
		public String toString(){
			return name + ": " + version;
		}

		@Override
		public boolean equals(Object obj) {
			boolean result = false;
			
			if(obj instanceof PCProgram)
			{
				PCProgram program = (PCProgram)obj;
				
				if(program.name.equals(this.name) && program.version.equals(this.version))
				{
					result = true;
				}
			}
			
			return result;
		}
		
		
	}
}
