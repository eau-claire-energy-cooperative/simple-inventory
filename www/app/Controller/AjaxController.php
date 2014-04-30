<?php
	
class AjaxController extends AppController {
    var $components = array('Session','Ping');
    var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Computer','Setting','Command','RestrictedProgram','ServiceMonitor');

	function checkRunning($name){
		$isRunning = $this->Ping->ping($name);
		$this->set('result',$isRunning);
	}
	
	function shutdown($computer,$restart = false){
		//pull in the system settings
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
		$this->Ping->shutdown_computer($computer,$restart, $settings);
	}
	
	function wol(){
	
		$this->Ping->wol($_SERVER['SERVER_ADDR'],$this->params['url']['mac']);
	}
	
	function setup_command($id){

		//get the command that goes with this id
		$command = $this->Command->find('first',array('conditions'=>array('Command.id'=>$id)));
		$this->set('command',$command);
	}
	
	function toggle_restricted($delete,$program)
	{
		if($delete == 'true')
		{
			$this->RestrictedProgram->query(sprintf('delete from restricted_programs where name ="%s"',$program));
		}
		else
		{
			$this->RestrictedProgram->create();
			$this->RestrictedProgram->set('name',$program);
			$this->RestrictedProgram->save();
		}
	}
	
	function toggle_monitoring($id){
		//get the computer in question
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));
		
		if($computer){
			
			if($computer['Computer']['EnableMonitoring'] == 'false')
			{
				$computer['Computer']['EnableMonitoring'] = 'true';
				$this->set('result',array('message'=>'Disable Monitoring'));
			}
			else
			{
				$computer['Computer']['EnableMonitoring'] = 'false';
				$this->set('result',array('message'=>'Enable Monitoring'));
			}
				
			$this->Computer->save($computer);
		}
	}
	
	function toggle_service_monitor($compid,$service){
		//check if this service exists
		$serviceExists = $this->ServiceMonitor->find('first',array('conditions'=>array('ServiceMonitor.comp_id'=>$compid,'ServiceMonitor.service'=>$service)));
		
		if($serviceExists)
		{
			//remove it
			$this->ServiceMonitor->delete($serviceExists['ServiceMonitor']['id']);
		}
		else
		{
			//create it
			$this->ServiceMonitor->create();
			$this->ServiceMonitor->set('comp_id',$compid);
			$this->ServiceMonitor->set('service',$service);
			$this->ServiceMonitor->save();
		}
	}
	
	function uploadDrivers($id){
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));
		
		$this->set('computer',$computer['Computer']);
		$this->set('id',$id);
	}
}
?>