package com.ecec.rweber.inventory.utils;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import org.json.simple.JSONObject;
import com.ecec.rweber.conductor.framework.GetSettings;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;

public class GetDBSettings implements GetSettings{

	@Override
	public Map<String, String> getSettings(Helper h, Map<String, String> params) {
		Map<String,String> result = new HashMap<String,String>();
		
		if(params.containsKey("inventory_url"))
		{
			ApiManager manager = ApiManager.getInstance(params.get("inventory_url"));
			
			//get all the settings
			JSONObject jsonO = manager.settings();
			
			if(jsonO != null && jsonO.get("type").equals(ApiManager.RESPONSE_SUCCESS))
			{
				JSONObject settings = (JSONObject)jsonO.get("result");
				Iterator<String> settingsIter = settings.keySet().iterator();
				
				String aSetting = null;
				while(settingsIter.hasNext())
				{
					aSetting = settingsIter.next();
					result.put(aSetting, settings.get(aSetting).toString());
				}
				
			}
		}
		
		return result;
	}

}
