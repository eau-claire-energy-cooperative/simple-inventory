package com.ecec.rweber.inventory.api;

import java.sql.Timestamp;
import java.util.HashMap;
import java.util.Map;

import org.json.simple.JSONObject;

public class ApiManager {

	private static ApiManager instance = null;
	public static final String RESPONSE_ERROR = "error";
	public static final String RESPONSE_SUCCESS = "success";
	
	public static final String INVENTORY_EXISTS = "exists";
	public static final String INVENTORY_UPDATE = "update";
	public static final String INVENTORY_ADD = "add";
	public static final String LOCATION_DEFAULT = "default";
	public static final String PROGRAMS_FIND = "get";
	public static final String PROGRAMS_CLEAR = "clear";
	public static final String PROGRAMS_ADD = "add";
	public static final String SERVICE_CLEAR = "clear";
	public static final String SERVICE_ADD = "add";
	public static final String SERVICE_FIND = "get";

	private String baseUrl = null;
	private Endpoint inventory = null;
	private Endpoint settings = null;
	private Endpoint log = null;
	private Endpoint location = null;
	private Endpoint programs = null;
	private Endpoint services = null;
	private Endpoint email = null;
	
	public static ApiManager getInstance(String url){
		
		if(instance == null)
		{
			instance = new ApiManager(url);
		}
		
		return instance;
	}
	
	private ApiManager(String url){
		baseUrl = url + "api/";
		
		inventory = new Endpoint(baseUrl,"inventory");
		settings = new Endpoint(baseUrl,"settings");
		log = new Endpoint(baseUrl,"add_log");
		location = new Endpoint(baseUrl,"location");
		programs = new Endpoint(baseUrl,"programs");
		services = new Endpoint(baseUrl,"services");
		email = new Endpoint(baseUrl,"send_email");
		
	}
	
	public JSONObject inventory(String action, Map<String,String> parameters){
		return inventory.sendRequest(action, parameters);
	}
	
	public JSONObject computer_exists(String name){
		Map<String,String> params = new HashMap<String,String>();
		params.put("computer", name);
		
		return inventory.sendRequest(ApiManager.INVENTORY_EXISTS,params);
	}
	
	public JSONObject location(String action){
		return location.sendRequest(action, null);
	}
	
	public JSONObject settings(){
		return settings.sendRequest("get", null);
	}
	
	public JSONObject log(String logger, String level, String message){
		Map<String,String> params = new HashMap<String,String>();
		params.put("date", new Timestamp(System.currentTimeMillis()).toString());
		params.put("logger", logger);
		params.put("level",level);
		params.put("message",message);
		
		
		return log.sendRequest(params);
	}
	
	public JSONObject programs(String action, Map<String,String> params){
		return programs.sendRequest(action, params);
	}
	
	public JSONObject services(String action, Map<String,String> params){
		return services.sendRequest(action, params);
	}
	
	public JSONObject send_email(String subject,String message){
		Map<String,String> params = new HashMap<String,String>();
		params.put("subject", subject);
		params.put("message", message);
		
		return email.sendRequest(params);
	}
}
