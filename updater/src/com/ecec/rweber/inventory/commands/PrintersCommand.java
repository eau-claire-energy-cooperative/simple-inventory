package com.ecec.rweber.inventory.commands;

import javax.print.PrintService;
import javax.print.PrintServiceLookup;
import javax.print.attribute.Attribute;
import javax.print.attribute.AttributeSet;

import org.hyperic.sigar.Sigar;

import com.ecec.rweber.inventory.utils.PCInfo;

public class PrintersCommand implements SigarCommand{

	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
	
		PrintService[] p = PrintServiceLookup.lookupPrintServices(null, null);
		
		for(int count = 0; count < p.length; count ++)
		{
			AttributeSet att = p[count].getAttributes();
			
			for (Attribute a : att.toArray()) {

				String attributeName;

				String attributeValue;

				attributeName = a.getName();

				attributeValue = att.get(a.getClass()).toString();

				System.out.println(attributeName + " : " + attributeValue);

				}		
		}
		
		return currentInfo;
	}

}
