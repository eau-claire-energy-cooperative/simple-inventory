package com.ecec.rweber.inventory.commands;

import org.hyperic.sigar.Sigar;

import com.ecec.rweber.inventory.utils.PCInfo;

public interface SigarCommand {

	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo);
}
