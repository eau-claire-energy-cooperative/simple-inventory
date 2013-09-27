package com.ecec.rweber.inventory.api;

import java.util.HashMap;
import java.util.Map;

import org.json.simple.JSONObject;

public class Test {

	public static void main(String[] args){
		ApiManager manager = ApiManager.getInstance("http://inventory.ecec.com/inventory/");
		
		Map<String,String> parameters = new HashMap<String,String>();
		parameters.put("subject","How are you");
		parameters.put("message","<p><b>Just a test message</b></p>");
		
		JSONObject result = manager.send_email("how are you", "<p><b>Just a test message</b></p>");
		
		System.out.println(result.toJSONString());
	}
}
