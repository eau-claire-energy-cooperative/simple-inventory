package com.ecec.rweber.inventory;

import com.ecec.rweber.conductor.framework.Conductor;

public class Main {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		
		Conductor c = new Conductor(Conductor.parseFlags(args));
		c.start();
	}

}
