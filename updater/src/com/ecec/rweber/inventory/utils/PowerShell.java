
package com.ecec.rweber.inventory.utils;

import java.io.BufferedReader;
import java.io.InputStreamReader;



public class PowerShell {

	public static PowerShellResponse executeCommand(String aCommand){
		PowerShellResponse result = new PowerShellResponse();
		String error = "";
		String output = "";
		
		String command = "powershell.exe  " + aCommand;
	  
		try{
			Process powerShellProcess = Runtime.getRuntime().exec(command);
		  
			// Getting the results
			powerShellProcess.getOutputStream().close();
			String line;
			
			
			BufferedReader stdout = new BufferedReader(new InputStreamReader(
				powerShellProcess.getInputStream()));
				while ((line = stdout.readLine()) != null) {
					output = output + line + "\n";
				}
			stdout.close();
		  
			BufferedReader stderr = new BufferedReader(new InputStreamReader(
				powerShellProcess.getErrorStream()));
				while ((line = stderr.readLine()) != null) {
					error = error + line;
				}
			stderr.close();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		result.setStdOutput(output);
		
		//check for errors
		if(!error.isEmpty())
		{
			result.setErrorOutput(output);
		}
		
	  return result;
	}
    
}