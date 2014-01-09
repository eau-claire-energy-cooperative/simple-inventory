package com.ecec.rweber.inventory.commands;

import java.util.HashMap;
import org.hyperic.sigar.Sigar;
import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.inventory.utils.PCInfo;


public class ModelInfoWindowsCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		//get the bios information

		try{
			currentInfo.addField("SerialNumber", jWMI.getWMIValue("select SerialNumber from Win32_BIOS", "SerialNumber"));
			currentInfo.addField("Manufacturer",jWMI.getWMIValue("select Manufacturer from Win32_BIOS", "Manufacturer"));
			
			//get the product information
			currentInfo.addField("Model", jWMI.getWMIValue("select Name from Win32_ComputerSystemProduct", "Name"));
		}
		catch(Exception e)
		{
			
		}
		
		return currentInfo;
	}

}
