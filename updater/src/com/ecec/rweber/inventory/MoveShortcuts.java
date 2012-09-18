package com.ecec.rweber.inventory;

import java.util.HashMap;

import java.util.List;

import org.jdom.Element;

import com.ecec.rweber.conductor.framework.Car;
import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.conductor.framework.datasources.file.FileDatasource;
import com.ecec.rweber.conductor.framework.datasources.file.FileRepository;
import com.ecec.rweber.conductor.framework.datasources.file.RepositoryFile;
import com.ecec.rweber.conductor.framework.datasources.file.SMBRepository;

public class MoveShortcuts extends Car{
	private FileDatasource localDir = null;
	private FileDatasource remoteDir = null;
	
	public MoveShortcuts(Element c, Helper h, HashMap<String, String> tParams) {
		super(c, h, tParams);
		
		String username = System.getProperty("user.name");
		
		HashMap<String,String> remoteParams = new HashMap<String,String>();
		remoteParams.put("path",this.parameters.get("remote_path") + username + "/");
		
		HashMap<String,String> localParams = new HashMap<String,String>();
		localParams.put("path","C:/Users/rweber/Desktop");
		
		//create the two datasources
		remoteDir = new SMBRepository("Remote Path",remoteParams);
			
		localDir = new FileRepository("Local Profile",localParams);
			
		
	}

	@Override
	protected void cleanup() {
		// TODO Auto-generated method stub
		
	}

	@Override
	protected void runImp(Helper arg0) {
		
		//delete files on the share and send new ones
		this.deleteRemote();
		this.sendCurrent();
	}

	private void deleteRemote(){
		List<RepositoryFile> files = remoteDir.listFiles();
		
		for(int count = 0; count < files.size(); count ++)
		{
			remoteDir.deleteFile(files.get(count));
		}
	}

	private void sendCurrent(){
		List<RepositoryFile> files = localDir.listFiles();
	
		for(int count = 0; count < files.size(); count ++)
		{
			if(!files.get(count).isDirectory())
			{
				localDir.sendFile(files.get(count), remoteDir);
			}
		}
	}
}
