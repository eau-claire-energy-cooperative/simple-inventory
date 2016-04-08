package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.OperatingSystem;
import org.hyperic.sigar.Sigar;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.inventory.utils.PCInfo;

public class OSWindowsCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		OperatingSystem os = OperatingSystem.getInstance();
		
		String arch = os.getArch();
		double version = Double.parseDouble(os.getVersion());
		try {
			String tempArch = jWMI.getWMIValue("select * from Win32_OperatingSystem", "OSArchitecture");
			String[] vTemp = jWMI.getWMIValue("select * from Win32_OperatingSystem", "Version").split("\\.");
			
			//try and use the wmi result, probably more accurate
			if(vTemp.length >= 2)
			{
				version = Double.parseDouble(vTemp[0] + "." + vTemp[1]);
			}
			
			//this might be blank, on XP systems it appears to be
			if(!tempArch.trim().equals(""))
			{
				arch = tempArch;
			}
			
		} catch (Exception e) {
			//don't do anything, we'll just the sigar arch value
		}
		
		//let's do a check for Windows
		String osDescription = os.getDescription();
		if(os.getDescription().startsWith("Microsoft Windows"))
		{
			if(version > 6.1 && version < 6.4)
			{
				osDescription = "Microsoft Windows 8";
			}
			else if(version >= 10)
			{
				osDescription = "Microsoft Windows 10";
			}
		}
		
		currentInfo.addField("OS",osDescription);
		currentInfo.addField("OS_Arch", arch);
		currentInfo.addField("OS_Version", version + "");
		currentInfo.addField("OS_Patch_Level",os.getPatchLevel());
		currentInfo.addField("OS_Vendor", os.getVendor());
		currentInfo.addField("OS_Code_Name", os.getVendorCodeName());
		
		return currentInfo;
	}
}
