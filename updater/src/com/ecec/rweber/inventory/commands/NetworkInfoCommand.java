package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.NetInfo;
import org.hyperic.sigar.NetInterfaceConfig;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;

import com.ecec.rweber.inventory.utils.PCInfo;

public class NetworkInfoCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		try {
			NetInterfaceConfig network = sigar.getNetInterfaceConfig(null);
			
			currentInfo.addField("IPaddress",  network.getAddress());
			currentInfo.addField("MACaddress", network.getHwaddr());
			
		} catch (SigarException e) {
			//do nothing
		}
		
		return currentInfo;
	}

}
