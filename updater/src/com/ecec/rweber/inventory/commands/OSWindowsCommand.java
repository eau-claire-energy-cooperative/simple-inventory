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
		try {
			String tempArch = jWMI.getWMIValue("select * from Win32_OperatingSystem", "OSArchitecture");
			
			//this might be blank, on XP systems it appears to be
			if(!tempArch.trim().equals(""))
			{
				arch = tempArch;
			}
			
		} catch (Exception e) {
			//don't do anything, we'll just the sigar arch value
		}
		
		currentInfo.addField("OS",os.getDescription());
		currentInfo.addField("OS_Arch", arch);
		currentInfo.addField("OS_Version", os.getVersion());
		currentInfo.addField("OS_Patch_Level",os.getPatchLevel());
		currentInfo.addField("OS_Vendor", os.getVendor());
		currentInfo.addField("OS_Code_Name", os.getVendorCodeName());
		
		return currentInfo;
	}

}
