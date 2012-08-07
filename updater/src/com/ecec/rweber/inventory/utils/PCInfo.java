package com.ecec.rweber.inventory.utils;

import java.util.HashMap;
import java.util.Iterator;

import org.hyperic.sigar.NetInfo;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.cmd.Shell;

public class PCInfo extends HashMap<String,String>{

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	public void addField(String field,String value){
		this.put(field, value);
	}
	
	public Iterator<String> getFields(){
		return this.keySet().iterator();
	}
	
	public static String getComputerName(){
		String result = null;
		
		Sigar sigar = new Shell().getSigar();
		
		try{
			NetInfo computerInfo = sigar.getNetInfo();
			
			result = computerInfo.getHostName();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		return result;
	}
}
