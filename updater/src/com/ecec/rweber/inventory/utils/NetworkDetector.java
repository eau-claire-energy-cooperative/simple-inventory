package com.ecec.rweber.inventory.utils;

import java.net.InetAddress;

import java.net.NetworkInterface;
import java.net.SocketException;
import java.util.Enumeration;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class NetworkDetector {

	public static boolean networkRunning(String networkIdent){
		boolean result = false;
		
		//create a regular expression
		networkIdent = networkIdent.replaceAll("x", "[0-255]");
		Pattern p = Pattern.compile(networkIdent);
		
		try{
			//first check if we have a valid network interface
			Enumeration<NetworkInterface> interfaces = NetworkInterface.getNetworkInterfaces();
			
			NetworkInterface ni = null;
			while(interfaces.hasMoreElements())
			{
				ni = interfaces.nextElement();
				
				if(ni.isUp() && !ni.isLoopback())
				{
				
					Enumeration<InetAddress> addresses = ni.getInetAddresses();
					String tempAddress = null;
					while(addresses.hasMoreElements())
					{
						tempAddress = addresses.nextElement().toString().substring(1);
						
						Matcher m = p.matcher(tempAddress);
						
						if(m.find())
						{
							result = true;
						}
					}
				}
			}
		}
		catch(SocketException se)
		{
			se.printStackTrace();
		}
		
		return result;
	}
	
	public static String getComputerName(){
		String result = "";
		
		try{
			result = InetAddress.getLocalHost().getHostName();
		}
		catch(Exception se)
		{
			se.printStackTrace();
		}
		
		return result;
	}
}