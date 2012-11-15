<?php
	
class AjaxController extends AppController {
    var $components = array('Session','Ping');
    var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Computer','Setting','Command');

	function checkRunning($name){
		$isRunning = $this->Ping->ping($name);
		$this->set('result',$isRunning);
	}
	
	function shutdown($computer,$restart = false){
		//pull in the system settings
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
		$exec_string = 'net rpc shutdown -I ' . $computer . ' -U ' . $settings['domain_username'] . '%' . $settings['domain_password'] . ' ';
		
		if($restart == 'true'){
			$exec_string = $exec_string . '-r ';
		}
		
		$exec_string = $exec_string . '-C "' . $settings['shutdown_message'] . '"';
		
		exec($exec_string);
	}
	
	function wol(){
	
		$this->Ping->wol($_SERVER['SERVER_ADDR'],$this->params['url']['mac']);
	}
	
	function setup_command($id){

		//get the command that goes with this id
		$command = $this->Command->find('first',array('conditions'=>array('Command.id'=>$id)));
		$this->set('command',$command);
	}
}
?>