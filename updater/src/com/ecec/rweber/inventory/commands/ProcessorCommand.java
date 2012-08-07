package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.CpuInfo;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;

import com.ecec.rweber.inventory.utils.PCInfo;

public class ProcessorCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		try {
			CpuInfo[] info = sigar.getCpuInfoList();
			CpuInfo processor = info[0];
			
			currentInfo.addField("CPU_Vendor", processor.getVendor());
			currentInfo.addField("CPU", processor.getModel());
			currentInfo.addField("CPU_Cores", processor.getTotalCores() + "");
			
		} catch (SigarException e) {
			//do nothing
		}
		
		return currentInfo;
	}

}
