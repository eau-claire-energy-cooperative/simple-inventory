package com.ecec.rweber.inventory.utils;

public class PowerShellResponse {
	private String stdOutput = null;
	private String errorOutput = null;
	private boolean isError = false;
	
	public PowerShellResponse(){
		
	}
	
	public void setStdOutput(String output){
		stdOutput = output;
	}
	
	public String getStdOutput(){
		return stdOutput;
	}
	
	public void setErrorOutput(String output){
		this.isError = true;
		errorOutput = output;
	}
	
	public String getErrorOutput(){
		return errorOutput;
	}
	
	public boolean hasError(){
		return isError;
	}
}
