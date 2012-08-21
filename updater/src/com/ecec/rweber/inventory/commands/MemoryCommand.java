package com.ecec.rweber.inventory.commands;

import java.text.DecimalFormat;

import org.hyperic.sigar.Mem;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;

import com.ecec.rweber.inventory.utils.PCInfo;

public class MemoryCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		DecimalFormat df = new DecimalFormat("#.###");

		//get the memory info on this PC
		Mem memory;
		try {
			memory = sigar.getMem();
			
			//memory is in bytes, divide by 1024 ^3 and round to nearest integer
			long totalMem = Math.round(memory.getTotal() / Math.pow(1024, 3));
			String totalFree = df.format(memory.getActualFree() / Math.pow(1024, 3));
			
			currentInfo.addField("Memory", totalMem + "");
			currentInfo.addField("MemoryFree", totalFree);
			
		} catch (SigarException e) {
			// don't do anything, we'll just return
			e.printStackTrace();
		}
		
		return currentInfo;
	}

}
