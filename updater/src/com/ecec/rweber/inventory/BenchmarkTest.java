package com.ecec.rweber.inventory;

import java.util.HashMap;
import java.util.List;

import org.hyperic.sigar.Mem;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.cmd.Shell;
import org.jdom.Element;

import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.exception.InvalidDatasourceException;
import com.ecec.rweber.conductor.framework.datasources.sql.SQLDatasource;
import com.ecec.rweber.inventory.utils.Database;
import com.ecec.rweber.inventory.utils.PCInfo;

public class BenchmarkTest extends Car{
	private Integer computerId = null;
	private SQLDatasource db = null;
	private Sigar sigar = null;
	
	public BenchmarkTest(Element c, Helper h, HashMap<String, String> tParams) {
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
		String insertString = "insert into temp_data (comp_id,processes,free_memory) values (?,?,?)";
		
		if(computerId != null)
		{
			logInfo("Gathering test data");
			long totalProcesses = 0;
			double memoryUsed = 0;
			try{
				//get the number of processes
				totalProcesses = sigar.getProcStat().getRunning();
		
				Mem memory = sigar.getMem();
				memoryUsed = memory.getFreePercent();
				
				
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
			
			db.executeUpdate(insertString, computerId,totalProcesses,memoryUsed);
		}
	}

}
