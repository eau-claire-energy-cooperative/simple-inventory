package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.OperatingSystem;
import org.hyperic.sigar.Sigar;
import com.ecec.rweber.inventory.utils.PCInfo;

public class OSLinuxCommand implements SigarCommand{

	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		OperatingSystem os = OperatingSystem.getInstance();
		
		currentInfo.addField("OS",os.getDescription());
		currentInfo.addField("OS_Arch", os.getArch());
		currentInfo.addField("OS_Version", os.getVersion());
		currentInfo.addField("OS_Patch_Level",os.getPatchLevel());
		currentInfo.addField("OS_Vendor", os.getVendor());
		currentInfo.addField("OS_Code_Name", os.getVendorCodeName());
		
		return currentInfo;
	}

}
