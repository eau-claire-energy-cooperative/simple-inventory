package com.ecec.rweber.inventory.commands;

import java.awt.GraphicsDevice;
import java.awt.GraphicsEnvironment;
import java.awt.HeadlessException;

import org.hyperic.sigar.Sigar;

import com.ecec.rweber.inventory.utils.PCInfo;

public class MonitorsCommand implements SigarCommand{

	@Override
	public PCInfo runCommand(Sigar sigar, PCInfo currentInfo) {
		
		//use the graphics info to find the number of displays
		int numScreens = 0;
		GraphicsEnvironment ge = GraphicsEnvironment.getLocalGraphicsEnvironment();
		try {
		    GraphicsDevice[] gs = ge.getScreenDevices();

		    // Get number of screens
		    numScreens = gs.length;
		} catch (HeadlessException e) {
		    // Is thrown if there are no screen devices - do nothing
		}
		
		currentInfo.addField("NumberOfMonitors", new String(numScreens + ""));
		
		return currentInfo;
	}

}
