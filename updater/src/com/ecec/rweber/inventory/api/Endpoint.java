package com.ecec.rweber.inventory.api;

import java.io.BufferedReader;

import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Map;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

public class Endpoint {
	
	protected String endpoint = null;

	public Endpoint(String baseUrl, String name){
		endpoint = baseUrl + name + "/";
	}
	
	public JSONObject sendRequest(Map<String,String> parameters){
		return this.sendRequest(null,parameters);
	}
	
	public JSONObject sendRequest(String action, Map<String,String> parameters){
		JSONObject result = null;
		JSONParser parser = new JSONParser();
		
		//catch for no action type endpoints
		String requestURL = null;
		if(action == null)
		{
			requestURL = endpoint;
		}
		else
		{
			 requestURL = endpoint + action + "/";
		}
		
		//create the json request
		JSONObject jsonRequest = new JSONObject();
		if(parameters != null)
		{
			jsonRequest = new JSONObject(parameters);
		}
		
		try{
			//send the request - this is pretty quick and dirty, should probably clean it up
			
			URL url = new URL(requestURL);
			HttpURLConnection conn = (HttpURLConnection) url.openConnection();
			conn.setDoInput(true);
			conn.setDoOutput(true);
			conn.setRequestMethod("POST");
			conn.setRequestProperty("Content-Length", String.valueOf(jsonRequest.toJSONString()));
			OutputStream os = conn.getOutputStream();
			os.write(jsonRequest.toJSONString().getBytes());
			
			//get the response
			BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));
			
			String response = "";
			while(reader.ready())
			{
				response = response + reader.readLine();
			}
			
			conn.disconnect();
			
			String resultString = response;
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
