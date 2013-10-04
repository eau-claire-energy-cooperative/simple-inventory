<?php
	
class DashboardController extends AppController {
	var $uses = array('Computer','Service','ServiceMonitor');
	var $helpers = array('Session','Html','Time');
	
	function index(){
		$onlineComputers = array();
		$offlineComputers = array();
		
		//first check on any computers that need monitoring
		$computerList = $this->Computer->find('all',array('conditions'=>array('Computer.EnableMonitoring'=>'true')));
		
		if($computerList)
		{
			foreach($computerList as $computer){
				if($computer['Computer']['IsAlive'] == 'true')
				{
					//check if this computer has any offline services
					$services = $this->_checkServices($computer['Computer']['id']);
					
					if(count($services) > 0)
					{
						$computer['OfflineServices'] = $services;
						
						$offlineComputers[] = $computer;
					}
					else
					{
						$onlineComputers[] = $computer;		
					}
				}
				else
				{
					$offlineComputers[] = $computer;
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
	