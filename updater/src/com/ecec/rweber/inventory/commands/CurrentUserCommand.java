package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;
import org.hyperic.sigar.Who;

import com.ecec.rweber.inventory.utils.PCInfo;

public class CurrentUserCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {

		currentInfo.addField("CurrentUser", System.getProperty("user.name"));
		
		return currentInfo;
	}

}
