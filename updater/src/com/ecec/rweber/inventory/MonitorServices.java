package com.ecec.rweber.inventory;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.jdom.Element;
import org.json.simple.JSONObject;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;
import com.ecec.rweber.inventory.utils.GetDBSettings;
import com.ecec.rweber.inventory.utils.PCInfo;

public class MonitorServices extends Car{
	private ApiManager api = null;
	private Integer computerId = null;
	private CarStateManager manager = null;
	
	public MonitorServices(Element c, Helper h, HashMap<String, String> tParams) {
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
		
		manager = new CarStateManager(h);
		
	}

	@Override
	protected void cleanup() {
		// TODO Auto-generated method stub
		
	}

	@Override
	protected void runImp(Helper arg0) {
		List<Element> updateServices = null;
		
		try {
			updateServices = jWMI.getWMIValues("select * from Win32_Service", "DisplayName, StartMode, State");
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		if(computerId != null)
		{
			//reuse these parameters, just reset the args
			Map<String,String> params = new HashMap<String,String>();
			params.put("id",computerId.toString());
			
			//insert each service into the database
			Element newValue = null;
			String serviceName = null;
			for(int count = 0; count < updateServices.size(); count ++)
			{
				newValue = updateServices.get(count);
				serviceName = newValue.getChildText("DisplayName");
				
				if(manager.settingExists(serviceName))
				{
					String oldValue = manager.getSetting(serviceName);
					
					//check that the service exists and that the state has changed
					if(oldValue != null && !oldValue.equals(newValue.getChildText("State")))
					{
						this.logInfo(PCInfo.getComputerName() + " service " + serviceName + " is now " + newValue.getChildText("State"));
						
						params.put("name",serviceName);
						params.put("mode",newValue.getChildText("StartMode"));
						params.put("status",newValue.getChildText("State"));
						
						api.services(ApiManager.SERVICE_UPDATE, params);
						
						//save this as part of the manager as well
						manager.saveState(serviceName, newValue.getChildText("State"));
					}
				}
				else
				{
					manager.saveState(serviceName, newValue.getChildText("State"));
				}
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	}

}
