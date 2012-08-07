package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.FileSystemUsage;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;

import com.ecec.rweber.inventory.utils.PCInfo;

public class DiskUsageCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		try {
			//get the C:\ drive
			FileSystemUsage fs = sigar.getFileSystemUsage("C:\\");
			
			currentInfo.addField("DiskSpace", fs.getTotal() + "");
			currentInfo.addField("DiskSpaceFree", fs.getFree() + "");
			
			
		} catch (SigarException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		return currentInfo;
	}

}
