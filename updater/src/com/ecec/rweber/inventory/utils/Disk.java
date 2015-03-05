package com.ecec.rweber.inventory.utils;

import java.util.HashMap;

public class Disk extends HashMap<String,String>{
	public String label = null;
	public String total_space = null;
	public String space_free = null;
	public String type = null;
	
	public Disk(String label, String total, String free){
		super();
		
		this.put("label",label);
		this.put("total_space", total);
		this.put("space_free", free);
		this.put("type","Local");
	}
	
	public Disk(String label, String total, String free, String type){
		super();
		
		this.put("label",label);
		this.put("total_space", total);
		this.put("space_free", free);
		this.put("type",type);
	}
	
}
