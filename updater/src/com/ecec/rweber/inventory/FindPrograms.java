package com.ecec.rweber.inventory;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import org.jdom.Element;

import com.citumpe.ctpTools.jWMI;
import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.exception.InvalidDatasourceException;
import com.ecec.rweber.conductor.framework.datasources.sql.SQLDatasource;
import com.ecec.rweber.inventory.utils.Database;
import com.ecec.rweber.inventory.utils.NetworkDetector;
import com.ecec.rweber.inventory.utils.PCInfo;
import com.ecec.rweber.utils.SettingsReader;

public class FindPrograms extends Car{
	private Integer computerId = null;
	private SQLDatasource db = null;
	
	public FindPrograms(Element c, Helper h, HashMap<String, String> tParams) {
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
		List<PCProgram> allPrograms = new ArrayList<PCProgram>();
		
		try{
			
			List<Element> wmi = jWMI.getWMIValues("select * from Win32_Product", "Name");
			
			Element temp = null;
			for(int i = 0; i < wmi.size(); i ++)
			{
				temp = wmi.get(i);
				allPrograms.add(new PCProgram(temp.getChildText("Name"),temp.getChildText("Name")));
					
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		if(computerId != null)
		{
			logInfo("Found " + allPrograms.size() + " programs for computer " + PCInfo.getComputerName());
			//clear out the current programs list
			db.executeUpdate("delete from programs where comp_id = ?", computerId);
			
			String updateString = "insert into programs (comp_id,program) values (?,?)";
			PCProgram p = null;
			for(int i = 0; i < allPrograms.size(); i ++)
			{
				//insert the program for this computer
				p = allPrograms.get(i);
				db.executeUpdate(updateString, computerId,p.name);
			}
		}
		else
		{
			logError("No Computer ID found for " + PCInfo.getComputerName() + ", can't update programs");
		}
	
	}

	public class PCProgram {
		public String name; 
		public String version;
		
		public PCProgram(String name, String version)
		{
			this.name = name;
			this.version = version;
		}
		
		public String toString(){
			return name + ": " + version;
		}
	}
}
