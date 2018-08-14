package com.ecec.rweber.inventory;

import java.io.File;

import com.ecec.rweber.conductor.framework.Conductor;

public class Main {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		//load the sigar library
		System.load(new File("sigar-bin/lib/" + System.mapLibraryName("sigar-x86-winnt")).getAbsolutePath());
		
		Conductor c = new Conductor(Conductor.parseFlags(args));
		c.start();
	}

}
