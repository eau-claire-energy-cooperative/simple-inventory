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


public class WindowsServices extends Car{
	private Integer computerId = null;
	private ApiManager api = null;
	
	public WindowsServices(Element c, Helper h, HashMap<String, String> tParams) {
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
		List<Element> wmi = null;
		
		try {
			wmi = jWMI.getWMIValues("select * from Win32_Service", "DisplayName, StartMode, State");
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		if(computerId != null)
		{
			//reuse these parameters, just reset the args
			Map<String,String> params = new HashMap<String,String>();
			params.put("id",computerId.toString());
			
			logInfo("Found " + wmi.size() + " services for computer " + PCInfo.getComputerName());
			
			//clear out the services list
			api.services(ApiManager.SERVICE_CLEAR, params);
			
			//insert each service into the database
			Element temp = null;
			for(int count = 0; count < wmi.size(); count ++)
			{
				temp = wmi.get(count);
				params.put("name",temp.getChildText("DisplayName"));
				params.put("mode",temp.getChildText("StartMode"));
				params.put("status",temp.getChildText("State"));
				
				api.services(ApiManager.SERVICE_ADD, params);
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	}

}
