package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;
import org.hyperic.sigar.Who;

import com.ecec.rweber.inventory.utils.PCInfo;

public class CurrentUserCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {

		String currentUser =  System.getProperty("user.name");
		
		if(currentUser.equals(PCInfo.getComputerName().toUpperCase() + "$"))
		{
			//no user is logged in, or using system account
			currentUser = "Local System Account";
		}
		
		currentInfo.addField("CurrentUser", currentUser);
		
		
		
		return currentInfo;
	}

}
