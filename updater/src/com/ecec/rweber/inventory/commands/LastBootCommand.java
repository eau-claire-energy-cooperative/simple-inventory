package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.Sigar;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.utils.SettingsReader;

public class LastBootCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		String lastBoot = "";
		
		try {
			 String lastBootString = jWMI.getWMIValue("select * from Win32_OperatingSystem", "LastBootUpTime");
			 
			//create date/time out of this string
			lastBoot = lastBootString.substring(0, 4) + "-" + lastBootString.substring(4, 6) + "-" + lastBootString.substring(6,8) + " " +
						lastBootString.substring(8,10) + ":" + lastBootString.substring(10,12) + ":" + lastBootString.substring(12,14);
			
		} catch (Exception e) {
			//don't do anything
		}
		
		currentInfo.addField("LastBootTime", lastBoot);
		
		return currentInfo;
	}

}
