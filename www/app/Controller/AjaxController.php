<?php

class AjaxController extends AppController {
  var $components = array('Session','Ping');
  var $helpers = array('Form', 'Js', "Lifecycle", "Markdown", "Time");
	var $layout = '';
	var $uses = array('Applications','Computer','Setting','Command','User');

	public function beforeFilter(){
	   $this->_check_authenticated();
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

  function assign_application($app_id){
    $this->layout = 'fancybox';

    //get the application
    $application = $this->Applications->find('first', array('conditions'=>array('Applications.id'=>$app_id)));

    //get a list of computers already assigned
    $assigned = array();
    foreach($application['Computer'] as $comp){
      $assigned[] = $comp['id'];
    }

    $this->set('application', $application['Applications']);

    //filter out already assigned from this list
    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'),
                                                       'conditions'=>array('NOT'=>array('Computer.id'=>$assigned)),
                                                       'order'=>array('Computer.ComputerName asc')));
    $this->set('computers', $allComputers);
  }

  function view_lifecycle($app_id){
    //load this application (lifecycle will follow)
    $application = $this->Applications->find('first', array('conditions'=>array('Applications.id'=>$app_id)));
    $this->set('application', $application);

    //get any other version of this application
    $allVersions = $this->Applications->find('all', array('conditions'=>array('Applications.name'=>$application['Applications']['name'])));

    // get version and total assigned info
    $totalVersions = array();
    $olderInstalls = 0;
    $newerInstalls = 0;
    foreach(array_values($allVersions) as $v){
      $totalVersions[] = $v['Applications']['version'];

      // if lower, count how many computers on this version
      if(version_compare($v['Applications']['version'], $application['Applications']['version']) == -1)
      {
        $olderInstalls = $olderInstalls + count($v['Computer']);
      }
      elseif (version_compare($v['Applications']['version'], $application['Applications']['version']) == 1) {
        $newerInstalls = $newerInstalls + count($v['Computer']);
      }
    }
    usort($totalVersions, 'version_compare');
    $this->set('total_versions', count($totalVersions));
    $this->set('highest_version', end($totalVersions));
    $this->set('older_installs', $olderInstalls);
    $this->set('newer_installs', $newerInstalls);
  }

  function add_disk($comp_id){
    $this->set('comp_id', $comp_id);
  }

  function assign_os_eol($name){
    $this->set('osName', $name);
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
