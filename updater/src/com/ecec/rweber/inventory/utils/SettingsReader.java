package com.ecec.rweber.inventory.utils;
import java.io.File;

import java.io.FileWriter;
import java.io.IOException;
import java.io.Reader;
import java.util.*;

import org.jdom2.Attribute;
import org.jdom2.Document;
import org.jdom2.Element;
import org.jdom2.input.SAXBuilder;
import org.jdom2.output.Format;
import org.jdom2.output.XMLOutputter;

public class SettingsReader {
	private Document settingsDoc = null;	//the original document
	private File inputFile = null;
	
	public SettingsReader(String file){
		settingsDoc = this.parseFile(file);
	}
	
	public SettingsReader(Reader in){
		settingsDoc = this.parseReader(in);
	}
	
	public void setSetting(String name, String value){

		//find out where in the document this setting is
		String[] nodes = this.breakString(name);
		Element root = settingsDoc.getRootElement();
		Element element = root;
		
		for(int count = 0; count < nodes.length; count ++)
		{
			if(element != null)
			{
				String currentNode = nodes[count];
				
				Element temp = null;
				if(currentNode.length() > 3 && currentNode.substring(currentNode.length()-3, currentNode.length()-2).equals("[") && currentNode.substring(currentNode.length()-1, currentNode.length()).equals("]"))
				{
					int index = Integer.parseInt(currentNode.substring(currentNode.length()-2, currentNode.length()-1));
					List tempList = element.getChildren(currentNode.substring(0,currentNode.length() -3));
					
					temp = (Element)tempList.get(index); 
				}
				else
				{
					temp = element.getChild(nodes[count]);
				}
	
				//we have to be on the last one for this to work
				if(temp == null && count == nodes.length - 1)
				{
					//maybe we need to find attributes
					element.setAttribute(nodes[count],value);
					element = null;
					
					settingsDoc.setRootElement(root);
					this.writeDocument();
				}
				else
				{
					element = temp;
				}
			}
		}
		
		if(element != null)
		{
			element.setText(value);
			settingsDoc.setRootElement(root);
			this.writeDocument();
		}
	}
	
	public String getSetting(String name){
		String result = null;
		
		//find out where in the document this setting is
		String[] nodes = this.breakString(name);
		Element element = settingsDoc.getRootElement();
		
		for(int count = 0; count < nodes.length; count ++)
		{
			if(element != null)
			{
				String currentNode = nodes[count];
				
				Element temp = null;
				if(currentNode.length() > 3 && currentNode.substring(currentNode.length()-3, currentNode.length()-2).equals("[") && currentNode.substring(currentNode.length()-1, currentNode.length()).equals("]"))
				{
					int index = Integer.parseInt(currentNode.substring(currentNode.length()-2, currentNode.length()-1));
					List tempList = element.getChildren(currentNode.substring(0,currentNode.length() -3));
					
					temp = (Element)tempList.get(index); 
				}
				else
				{
					temp = element.getChild(nodes[count]);
				}
	
				//we have to be on the last one for this to work
				if(temp == null && count == nodes.length - 1)
				{
					//maybe we need to find attributes
					result = element.getAttributeValue(nodes[count]);
					element = null;
				}
				else
				{
					element = temp;
				}
			}
		}
		
		if(element != null)
		{
			//let's get the value for the result
			result = element.getText();
		}
		
		return result;
	}
	
	public List getSettings(String name){
		List result = null;
		
		List<Element> foundList = this.getElements(name);
		HashMap tempMap = null;
		if(foundList != null)
		{
			result = new ArrayList();
			tempMap = new HashMap();
			
			for(int count = 0; count < foundList.size(); count ++)
			{
				tempMap = new HashMap();
				
				List attributes = (foundList.get(count)).getAttributes();
				
				for(int i = 0; i < attributes.size(); i ++)
				{
					Attribute a = (Attribute)attributes.get(i);
					
					tempMap.put(a.getName(),a.getValue());
				}
				
				result.add(tempMap);
			}
		}
		
		return result;
	}
	
	public List<Element> getElements(String name){
		
		//find out where in the document this setting is
		String[] nodes = this.breakString(name);
		Element element = settingsDoc.getRootElement();
		List foundList = null;

		for(int count = 0; count < nodes.length; count ++)
		{
			if(element != null)
			{
				if(count == nodes.length - 1)
				{
					//we need to get the list
					foundList = element.getChildren(nodes[count]);
				}
				else
				{
					String currentNode = nodes[count];
					
					Element temp = null;
					if(currentNode.substring(currentNode.length()-3, currentNode.length()-2).equals("[") && currentNode.substring(currentNode.length()-1, currentNode.length()).equals("]"))
					{
						int index = Integer.parseInt(currentNode.substring(currentNode.length()-2, currentNode.length()-1));
						List tempList = element.getChildren(currentNode.substring(0,currentNode.length() -3));
						
						element = (Element)tempList.get(index); 
					}
					else
					{
						element = element.getChild(nodes[count]);
					}
				}
			}
		}

		return foundList;
	}
	
	public boolean hasSetting(String name){
		if(this.getSetting(name) != null)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	private String[] breakString(String name){
		return name.split("\\.");
	}
	
	private Document parseReader(Reader input){
		Document result = null;
		
		SAXBuilder builder = new SAXBuilder();
		
		try{
			result = builder.build(input);
		}
		catch(Exception e)
		{
			//print that the reader could not be parsed
			System.out.println("Bad Input: " + input);
			e.printStackTrace();
		}
		
		return result;
	}
	
	private Document parseFile(String input){
		Document result = null;
		
		SAXBuilder builder = new SAXBuilder();
		
		try{
			inputFile = new File(input);
			result = builder.build(inputFile);
		}
		catch(Exception e)
		{
			//print that the url could not be found
			System.out.println("Bad File: " + input);
			e.printStackTrace();
		}
		
		return result;
	}
	
	private void writeDocument(){
		if(inputFile != null)
		{
			XMLOutputter xmlOutput = new XMLOutputter();
			 
			// display nice nice
			xmlOutput.setFormat(Format.getPrettyFormat());
			try {
				xmlOutput.output(settingsDoc, new FileWriter(inputFile));
			} catch (IOException e) {
				
				System.out.println("Error writing: " + inputFile.getName());
				e.printStackTrace();
			}
		}
	}
}