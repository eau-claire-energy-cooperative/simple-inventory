<?php
	
class AjaxController extends AppController {
    var $components = array('Session','Ping');
    var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Computer','Setting','Command','RestrictedProgram');

	public function beforeFilter(){
	    //check if we are using a login method
	    if(!$this->Session->check('authenticated')){
	        //check if we are using a login method
	        $loginMethod = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'auth_type')));
	        
	        if(isset($loginMethod) && trim($loginMethod['Setting']['value']) == 'none')
	        {
	            //we aren't authenticating, just keep moving
	            $this->Session->write('authenticated','true');
	        }
	        //check, we may already be trying to go to the login page
	        else if($this->action != 'login')
	        {
	            //we need to forward to the login page
	            $this->redirect(array('controller'=>'inventory','action'=>'login'));
	        }
	    }
	}
	
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
	
	function new_license(){
	    
	    //get a list of all computers
	    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'), 'order'=>array('Computer.ComputerName asc')));
	    $this->set('computers', $allComputers);
	    
	}
	
	function move_license($license_id, $current_comp){
	    
	    $this->set('license_id', $license_id);
	    $this->set('current_comp', $current_comp);
	    
	    //get a list of all computers
	    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'), 'order'=>array('Computer.ComputerName asc')));
	    $this->set('computers', $allComputers);
	    
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
	
	function uploadDrivers($id){
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));
		
		$this->set('computer',$computer['Computer']);
		$this->set('id',$id);
	}
}
?>