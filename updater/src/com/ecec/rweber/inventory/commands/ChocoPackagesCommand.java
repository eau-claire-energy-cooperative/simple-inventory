package com.ecec.rweber.inventory.commands;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import org.hyperic.sigar.Sigar;

import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.inventory.utils.PowerShell;
import com.ecec.rweber.inventory.utils.PowerShellResponse;

public class ChocoPackagesCommand implements SigarCommand{

	public ChocoPackagesCommand(){
		
	}
	
	private List<ChocoPackage> parsePackages(String output){
		List<ChocoPackage> result = new ArrayList<ChocoPackage>();
		String[] outputArray = output.split("\\n");
		
		for(int count = 0; count < outputArray.length; count ++)
		{
			result.add(new ChocoPackage(outputArray[count]));
		}
		
		return result;
	}
	
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		int needsUpdating = 0;
			
		PowerShellResponse output = PowerShell.executeCommand("choco outdated --limit-output");
		
		if(!output.hasError())
		{
			//parse the output into a list
			List<ChocoPackage> packages = this.parsePackages(output.getStdOutput());
			Iterator<ChocoPackage> iter = packages.iterator();
			ChocoPackage p = null;
			
			//figure out which need updating
			while(iter.hasNext())
			{
				p = iter.next();
				
				if(p.needsUpdate())
				{
					needsUpdating ++;
				}
			}
		}
		
		currentInfo.addField("ApplicationUpdates", needsUpdating + "");
		
		return currentInfo;
	}
	
	class ChocoPackage {
		private String name = null;
		private Version currentVersion = null;
		private Version availableVersion = null;
		
		public ChocoPackage(String name,String cVersion, String aVersion){
			this.name = name;
			this.currentVersion = new Version(cVersion);
			this.availableVersion = new Version(aVersion);
		}
		
		public ChocoPackage(String powerShellOutput){
			String[] splitString = powerShellOutput.split("\\|");
			
			name = splitString[0];
			currentVersion = new Version(splitString[1]);
			availableVersion = new Version(splitString[2]);
		}
		
		public boolean needsUpdate(){
			return availableVersion.compareTo(currentVersion) > 0;
		}
	
		public String getName(){
			return this.name;
		}
	}
	
	//thanks stacktrace - https://stackoverflow.com/questions/198431/how-do-you-compare-two-version-strings-in-java
	class Version implements Comparable<Version> {
		private String version;

	    public Version(String version) {
	        if(version == null)
	            throw new IllegalArgumentException("Version can not be null");
	        if(!version.matches("[0-9]+(\\.[0-9]+)*"))
	            throw new IllegalArgumentException("Invalid version format");
	        this.version = version;
	    }

	    public final String getVersion() {
	        return this.version;
	    }

	    public int compareTo(Version that) {
	        if(that == null)
	            return 1;
	        
	        String[] thisParts = this.getVersion().split("\\.");
	        String[] thatParts = that.getVersion().split("\\.");
	        int length = Math.max(thisParts.length, thatParts.length);
	        
	        for(int i = 0; i < length; i++) {
	            int thisPart = i < thisParts.length ?
	                Integer.parseInt(thisParts[i]) : 0;
	            int thatPart = i < thatParts.length ?
	                Integer.parseInt(thatParts[i]) : 0;
	            if(thisPart < thatPart)
	                return -1;
	            if(thisPart > thatPart)
	                return 1;
	        }
	        
	        return 0;
	    }

	    @Override 
	    public boolean equals(Object that) {
	    	
	    	if(this == that)
	            return true;
	        if(that == null)
	            return false;
	        if(this.getClass() != that.getClass())
	            return false;
	        
	        return this.compareTo((Version) that) == 0;
	    }
	}
}
