package com.ecec.rweber.inventory;

import java.util.HashMap;
import java.util.List;

import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.cmd.Shell;
import org.jdom.Element;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.exception.InvalidDatasourceException;
import com.ecec.rweber.conductor.framework.datasources.sql.SQLDatasource;
import com.ecec.rweber.inventory.FindPrograms.PCProgram;
import com.ecec.rweber.inventory.utils.Database;
import com.ecec.rweber.inventory.utils.PCInfo;

public class Benchmark extends Car{
	private Integer computerId = null;
	private SQLDatasource db = null;
	private Sigar sigar = null;
	
	public Benchmark(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
		
		//create db connection
		try {
			db = h.getSQLDatasource("inventory");
			
		} catch (InvalidDatasourceException e) {
			logError("Can't connect to Inventory DB");
			e.printStackTrace();
		}
		
		//create Sigar for PC info
		sigar = new Shell().getSigar();
		
		//find the computer ID in the database
		String computerName = PCInfo.getComputerName();
		
		if(computerName != null)
		{
			//get this computer's ID based on the name
			List<HashMap<String,String>> queryResults = db.executeQuery("select ID from " + Database.COMPUTER + " where ComputerName = ?", computerName);
			
			if(!queryResults.isEmpty())
			{
				computerId= new Integer(queryResults.get(0).get("ID"));
			}
		}
	}

	@Override
	protected void cleanup() {
		db.disconnect();
	}

	@Override
	protected void runImp(Helper arg0) {
		//first update the windows index
		double windowsIndex = this.updateWindowsIndex();
		logDebug("Windows Index = " + windowsIndex);
	}
	
	private double updateWindowsIndex(){
		//get the windows experience index number
		double windowsIndex = 0;
		
		try{
			
			String fullString = jWMI.getWMIValue("select * From Win32_WinSAT", "CPUScore, MemoryScore");
			
			//make sure this is not empty (may happen on XP machines)
			if(!fullString.trim().equals(""))
			{
				String[] splitString = fullString.split("\\n");
				
				String aLine = null;
				for(int i = 0; i < splitString.length; i ++)
				{
					windowsIndex = windowsIndex + Double.parseDouble(splitString[i]);	
				}
				
				//get an average
				windowsIndex = windowsIndex / splitString.length;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		if(windowsIndex != 0)
		{
			//update the windows index in the DB
			db.executeUpdate("update computer set WindowsIndex = ? where id = ?", windowsIndex,computerId);
		}
		
		return windowsIndex;
	}

}
