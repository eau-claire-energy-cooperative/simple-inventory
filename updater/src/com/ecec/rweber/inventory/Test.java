package com.ecec.rweber.inventory;

import org.json.simple.JSONObject;
import org.json.simple.JsonObject;

import com.ecec.rweber.inventory.api.ApiManager;

public class Test {

	public static void main(String[] args) {
		ApiManager test = ApiManager.getInstance("https://spiderman.ecec.com/inventory");
		
		JSONObject result = test.computer_exists("IT01-2014");
		
		//System.out.println(result.toJSONString());
		
		test.log("Test Logger", "INFO", "Test message");
	}

}
