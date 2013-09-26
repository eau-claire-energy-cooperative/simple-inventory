package com.ecec.rweber.inventory.utils;

import org.apache.log4j.AppenderSkeleton;
import org.apache.log4j.spi.LoggingEvent;

import com.ecec.rweber.conductor.framework.Helper;
import com.ecec.rweber.inventory.api.ApiManager;

public class WebsiteLogger extends AppenderSkeleton{
	private ApiManager manager = null;
	
	public WebsiteLogger(Helper h){
		manager = ApiManager.getInstance(h.settings.getSetting("log.custom.url"));
		
		//check to see if this works
		manager.settings();
	}

	@Override
	public void close() {
		
	}

	@Override
	public boolean requiresLayout() {
		// TODO Auto-generated method stub
		return false;
	}

	@Override
	protected void append(LoggingEvent event) {
		manager.log(event.getLoggerName(), event.getLevel().toString(), event.getMessage().toString());
	}
}
