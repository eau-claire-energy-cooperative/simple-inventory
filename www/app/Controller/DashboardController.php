<?php
	
class DashboardController extends AppController {
	var $uses = array('Computer','Service','ServiceMonitor','Setting','TriggeredAlarm');
	var $helpers = array('Session','Html','Time','DiskSpace');
	
	function index(){
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
		$onlineComputers = array();
		$offlineComputers = array();
		
		//first check on any computers that need monitoring
		$computerList = $this->Computer->find('all',array('conditions'=>array('Computer.EnableMonitoring'=>'true'),'order'=>array('Computer.ComputerName')));
		
		if($computerList)
		{
			foreach($computerList as $computer){
				$isOffline = false;
				
				if($computer['Computer']['IsAlive'] == 'true')
				{
					//check if this computer has any offline services
					$services = $this->_checkServices($computer['Computer']['id']);
					
					if(count($services) > 0)
					{
						$computer['OfflineServices'] = $services;
						
						$isOffline = true;
					}
					
					//check disk alerts
					$computer['DiskAlert'] = array();
					foreach($computer['Disk'] as $aDisk){
						
						if(($aDisk['space_free']/$aDisk['total_space']) * 100 < $settings['monitor_disk_space_warning'])
						{
							$computer['DiskAlert'][] = $aDisk; 
							$isOffline = true;
						}
					}
					
					//get any file alerts
					$fileAlarms = $this->TriggeredAlarm->find('all',array('conditions'=>array('TriggeredAlarm.comp_id' => $computer['Computer']['id'],'TriggeredAlarm.type'=>'file')));
					
					if($fileAlarms)
					{
						$computer['FileAlert']  = $fileAlarms;
						$isOffline = true;
					}
					else {
						$computer['FileAlert'] = array();
					}
					
				}
				else
				{
					$isOffline = true;
				}
				
				if($isOffline)
				{
					$offlineComputers[] = $computer;
				}
				else
				{
					$onlineComputers[] = $computer;
				}
			}
		}
		
		$this->set('online',$onlineComputers);
		$this->set('offline',$offlineComputers);
	}
	
	function _checkServices($id){
		$result = array();
		
		//figure out what services we need to check
		$serviceMonitors = $this->ServiceMonitor->find('all',array('conditions'=>array('ServiceMonitor.comp_id'=>$id)));
		
		if($serviceMonitors)
		{
			$allServices = $this->Service->find('list',array('fields'=>array('Service.name','Service.status'),'conditions'=>array('comp_id'=>$id)));
		
			foreach($serviceMonitors as $monitor){
				
				if(array_key_exists($monitor['ServiceMonitor']['service'], $allServices))
				{
					//see if the service is running
					if($monitor['ServiceMonitor']['isalive'] == 'false')
					{
						//the service is not alive, add to array
						$result[] = $monitor['ServiceMonitor']['service'];
					}
					
				}
			}
		}
		
		return $result;
	}
}
?>
	