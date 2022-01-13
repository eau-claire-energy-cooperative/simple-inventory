<?php

class AjaxController extends AppController {
  var $components = array('Session','Ping');
  var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Applications','Computer','Setting','Command','Programs','User');

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

	function checkRunning($id){
	  //get the IP of the device
	  $computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));

    $isRunning = $result = array('transmitted'=>1,'received'=>0);
    if($computer)
    {
	     $isRunning = $this->Ping->ping($computer['Computer']['IPaddress']);
    }

    $this->set('result',$isRunning);
	}

	function wol(){

		$this->Ping->wol($_SERVER['SERVER_ADDR'],$this->params['url']['mac']);
	}

	function setup_command($id){
        $this->layout = 'fancybox';

		//get the command that goes with this id
		$command = $this->Command->find('first',array('conditions'=>array('Command.id'=>$id)));
		$this->set('command',$command);
	}

	function new_license(){
	    $this->layout = 'fancybox';

	    //get a list of all computers
	    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'), 'order'=>array('Computer.ComputerName asc')));
	    $allComputers[0] = 'NO COMPUTER - UNASSIGNED';

	    $this->set('computers', $allComputers);

	}

	function move_license($license_id, $current_comp){
	    $this->layout = 'fancybox';

	    $this->set('license_id', $license_id);
	    $this->set('current_comp', $current_comp);

	    //get a list of all computers
	    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'), 'order'=>array('Computer.ComputerName asc')));
	    $allComputers[0] = 'NO COMPUTER - UNASSIGNED';

	    $this->set('computers', $allComputers);

	}

	function toggle_application_monitor($app_id, $monitor)
	{
			$this->Applications->query(sprintf('update applications set monitoring = "%s" where id = "%d"', $monitor, $app_id));

      $this->set('result', array('success'=>'true'));
	}

  function assign_program($prog_version, $prog_name){
    $this->layout = 'fancybox';

    //get the program from the name
    $program = $this->Programs->find('all', array('conditions'=>array('Programs.program'=>$prog_name, 'Programs.version'=>$prog_version)));

    //get a list of already assigned devices
    $assigned = array();
    foreach($program as $aProg)
    {
      $assigned[] = $aProg['Computer']['id'];
    }

    $this->set('program', $program[0]['Programs']['program']);
    $this->set('program_version', $program[0]['Programs']['version']);

    //filter out already assigned from this list
    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'),
                                                       'conditions'=>array('NOT'=>array('Computer.id'=>$assigned)),
                                                       'order'=>array('Computer.ComputerName asc')));
    $this->set('computers', $allComputers);
  }

	function uploadDrivers($id){
	  $this->layout = 'fancybox';
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));

		$this->set('computer',$computer['Computer']);
		$this->set('id',$id);
	}

	function setProfileImage(){
	    $this->layout = 'fancybox';

	    //get the current url
	    $aUser = $this->User->find('first',array('conditions'=>array('User.username'=>$this->Session->read('User.username'))));

	    $this->set('username', $aUser['User']['gravatar']);
	}
}
?>
