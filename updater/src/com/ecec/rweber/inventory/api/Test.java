package com.ecec.rweber.inventory.api;

import java.util.HashMap;
import java.util.Map;

import org.json.simple.JSONObject;

public class Test {

	public static void main(String[] args){
		ApiManager manager = ApiManager.getInstance("http://inventory.ecec.com/inventory/");
		
		Map<String,String> parameters = new HashMap<String,String>();
		parameters.put("id","116");
		parameters.put("program","Test Program");
		
		JSONObject result = manager.services(ApiManager.PROGRAMS_CLEAR, parameters);
		
		System.out.println(result.toJSONString());
	}
}
