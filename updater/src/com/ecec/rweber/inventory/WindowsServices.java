package com.ecec.rweber.inventory;

import java.util.HashMap;
import java.util.List;

import org.jdom.Element;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.exception.InvalidDatasourceException;
import com.ecec.rweber.conductor.framework.datasources.sql.SQLDatasource;
import com.ecec.rweber.inventory.FindPrograms.PCProgram;
import com.ecec.rweber.inventory.utils.Database;
import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.utils.SettingsReader;


public class WindowsServices extends Car{
	private Integer computerId = null;
	private SQLDatasource db = null;
	
	public WindowsServices(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
		
		//create db connection
		try {
			db = h.getSQLDatasource("inventory");
			
		} catch (InvalidDatasourceException e) {
			logError("Can't connect to Inventory DB");
			e.printStackTrace();
		}
		
		
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
		String updateStatement = "insert into " + Database.SERVICES +  " (comp_id,name,startmode,status) values (?,?,?,?)";
		List<Element> wmi = null;
		
		try {
			wmi = jWMI.getWMIValues("select * from Win32_Service", "DisplayName, StartMode, State");
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		if(computerId != null)
		{
			logInfo("Found " + wmi.size() + " services for computer " + PCInfo.getComputerName());
			//clear out the services list
			db.executeUpdate("delete from " + Database.SERVICES + " where comp_id = ?", computerId);
			
			//insert each service into the database
			Element temp = null;
			for(int count = 0; count < wmi.size(); count ++)
			{
				temp = wmi.get(count);
				
				db.executeUpdate(updateStatement, computerId,temp.getChildText("DisplayName"),temp.getChildText("StartMode"),temp.getChildText("State"));
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	}

}
