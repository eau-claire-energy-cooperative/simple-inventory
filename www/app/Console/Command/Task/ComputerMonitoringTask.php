<?php
class ComputerMonitoringTask extends AppShell {
    public $uses = array('Computer','ServiceMonitor','Service','TriggeredAlarm');
	
	var $settings = null;
	
    public function execute($params) {
		$this->settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));

		//first check on any computers that need monitoring
		$computerList = $this->Computer->find('all',array('conditions'=>array('Computer.EnableMonitoring'=>'true')));
		
		if($computerList)
		{
			foreach($computerList as $computer){
				
				$alarms = $this->TriggeredAlarm->find('list',array('fields'=>array('TriggeredAlarm.alarm','TriggeredAlarm.id'),'conditions'=>array("TriggeredAlarm.comp_id"=>$computer['Computer']['id'])));
				
				if(date('U',strtotime($computer['Computer']['LastUpdated'])) < time() - 345)
				{
					if($computer['Computer']['IsAlive'] == 'true')
					{
						//computer has gone offline, alert the admin and set to false
						$this->Computer->create();
						$this->Computer->id = $computer['Computer']['id'];
						$computer['Computer']['IsAlive'] = 'false';
						$this->Computer->save($computer);
						
						$this->log('Computer ' . $computer['Computer']['ComputerName'] . ' has gone offline');
						$this->_triggerAction($computer['Computer']['ComputerName'] . ' Has Gone Offline','Monitoring has detected that the computer: ' . $computer['Computer']['ComputerName'] . ' with IP address: ' . $computer['Computer']['IPaddress'] . ' has gone offline.');
					}	
				}
				else
				{
					if($computer['Computer']['IsAlive'] == 'false')
					{
						//computer has come online, alert admins and set to true
						$this->Computer->create();
						$this->Computer->id = $computer['Computer']['id'];
						$computer['Computer']['IsAlive'] = 'true';
						$this->Computer->save($computer);
						
						$this->log('Computer ' . $computer['Computer']['ComputerName'] . ' has come online');
						$this->_triggerAction($computer['Computer']['ComputerName'] . ' Is Online','Monitoring has detected that the computer: ' . $computer['Computer']['ComputerName'] . ' with IP address: ' . $computer['Computer']['IPaddress'] . ' is now online.');
						
					}
					else
					{
						//computer is still online from before, check services
						$this->_checkServices($computer['Computer']['id'],$computer['Computer']['ComputerName']);
						
						//also check disk space
						if(($computer['Computer']['DiskSpaceFree']/$computer['Computer']['DiskSpace']) * 100 <= $this->settings['monitor_disk_space_warning'] && !isset($alarms['disk_space_warning_c']))
						{
							$this->log($computer['Computer']['ComputerName'] . ' Disk Space Warning');
							$this->_triggerAction($computer['Computer']['ComputerName'] . ' Disk Space Warning', 'The computer ' . $computer['Computer']['ComputerName'] . ' has ' . $this->settings['monitor_disk_space_warning'] . '% or less disk space remaining');
							
							$this->TriggeredAlarm->create();
							$this->TriggeredAlarm->set('comp_id',$computer['Computer']['id']);
							$this->TriggeredAlarm->set('alarm',"disk_space_warning_c");
							$this->TriggeredAlarm->save();
						}
						else if(($computer['Computer']['DiskSpaceFree']/$computer['Computer']['DiskSpace']) * 100 > $this->settings['monitor_disk_space_warning'] && isset($alarms['disk_space_warning_c']))
						{
							$this->TriggeredAlarm->delete($alarms['disk_space_warning_c']);
						}
					}	
				}
			}
		}
    }

	function _checkServices($compid,$name){
		//figure out what services we need to check
		$serviceMonitors = $this->ServiceMonitor->find('all',array('conditions'=>array('ServiceMonitor.comp_id'=>$compid)));
		
		if($serviceMonitors)
		{
			$allServices = $this->Service->find('list',array('fields'=>array('Service.name','Service.status'),'conditions'=>array('comp_id'=>$compid)));
		
			foreach($serviceMonitors as $monitor){
				
				if(array_key_exists($monitor['ServiceMonitor']['service'], $allServices))
				{
					//see if the service is running
					if($allServices[$monitor['ServiceMonitor']['service']] != 'Running' && $monitor['ServiceMonitor']['isalive'] == 'true')
					{
						//service was a live, not it isn't
						$this->out("Service " . $monitor['ServiceMonitor']['service'] . ' on ' . $name . ' has stopped');
						$this->_triggerAction($monitor['ServiceMonitor']['service'] . ' On ' . $name . ' Has Stopped','The service ' . $monitor['ServiceMonitor']['service'] . ' on computer '. $name . ' has stopped');
						
						$monitor['ServiceMonitor']['isalive'] = 'false';
						$this->ServiceMonitor->save($monitor);
					}
					else if($allServices[$monitor['ServiceMonitor']['service']] == 'Running' && $monitor['ServiceMonitor']['isalive'] == 'false')
					{
						//service just came back online
						$this->out("Service " . $monitor['ServiceMonitor']['service'] . ' on ' . $name . ' is running');
						$this->_triggerAction($monitor['ServiceMonitor']['service'] . ' On ' . $name . ' Is Online','The service ' . $monitor['ServiceMonitor']['service'] . ' on computer '. $name . ' is now running');
						
						$monitor['ServiceMonitor']['isalive'] = 'true';
						$this->ServiceMonitor->save($monitor);
					}
				}
				else
				{
					//has this service been uninstalled?
				}
			}
		}
	}

	function _triggerAction($subject,$message){
		
		if($this->settings['monitoring_email'] == 'true')
		{
			$this->sendMail($subject,$message);
		}
		
		if(trim($this->settings['monitoring_script']) != '')
		{
			exec($this->settings['monitoring_script'] . ' "' . $subject . '" "' . $message . '"');
		}
	}
}
?>