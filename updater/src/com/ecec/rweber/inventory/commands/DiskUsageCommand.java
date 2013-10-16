package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.FileSystem;
import org.hyperic.sigar.FileSystemUsage;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;

import com.ecec.rweber.inventory.utils.Disk;
import com.ecec.rweber.inventory.utils.PCInfo;

public class DiskUsageCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		try {
			//get the local drives
			FileSystem fs = null;
			FileSystem[] allDisks = sigar.getFileSystemList();
			
			for(int count = 0; count < allDisks.length; count ++)
			{
				fs = allDisks[count];
				
				if(fs.getType() == FileSystem.TYPE_LOCAL_DISK)
				{
					FileSystemUsage fsUsage = sigar.getFileSystemUsage(fs.getDirName());
					
					currentInfo.addDisk(new Disk(fs.getDirName(),fsUsage.getTotal() + "", fsUsage.getFree() + ""));
				}
			}			
			
		} catch (SigarException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		return currentInfo;
	}

}
