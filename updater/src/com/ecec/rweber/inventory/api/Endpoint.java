package com.ecec.rweber.inventory.api;


import java.util.Map;
import javax.ws.rs.client.Client;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.Entity;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class Endpoint {
	
	protected String endpoint = null;
	private Client http = null;
	
	public Endpoint(String baseUrl, String name){
		endpoint = baseUrl + name + "/";
		
		http = ClientBuilder.newClient();
	}
	
	public JSONObject sendRequest(Map<String,String> parameters){
		return this.sendRequest(null,parameters);
	}
	
	public JSONObject sendRequest(String action, Map<String,String> parameters){
		JSONObject result = null;
		JSONParser parser = new JSONParser();
		
		WebTarget target = http.target(this.endpoint);
		
		//catch for no action type endpoints
		if(action != null)
		{
			target = target.path(action);
		}
		
		//create the json request
		JSONObject jsonRequest = new JSONObject();
		if(parameters != null)
		{
			jsonRequest = new JSONObject(parameters);
		}
		
		try{
			
			Response response = target.request().post(Entity.entity(jsonRequest.toJSONString(), MediaType.APPLICATION_JSON_TYPE));
			
			String resultString = response.readEntity(String.class);
			//System.out.println(resultString);
			result = (JSONObject) parser.parse(resultString);
			
			
		}
		catch(ClassCastException e){
			//don't do anything
		} catch (Exception e) {
			// TODO Auto-generated catch block
			//e.printStackTrace();
		}
		
		return result;
	}
}
