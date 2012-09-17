<?php
	
class AjaxController extends AppController {
    var $components = array('Session','Ping');
    var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Computer','Setting');

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
}
?>