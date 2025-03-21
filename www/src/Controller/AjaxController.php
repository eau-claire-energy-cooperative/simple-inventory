<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class AjaxController extends AppController {

  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('Ping');
    $this->viewBuilder()->setLayout('ajax');
  }

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function addDisk($comp_id){
    $this->set('comp_id', $comp_id);
  }

  function assignLicenseKey($license_id, $license_key_id){
      $this->viewBuilder()->setLayout('fancybox');

      $this->set('license_id', $license_id);
	    $this->set('license_key_id', $license_key_id);

	    //get a list of all computers
	    $allComputers = $this->fetchTable('Computer')->find('list', ['keyField'=>'id',
                                                                   'valueField'=>'ComputerName',
                                                                   'order'=>['Computer.ComputerName asc']])->toArray();
	    $allComputers = [0=>'NO COMPUTER - UNASSIGNED'] + $allComputers;

	    $this->set('computers', $allComputers);

	}

  function checkRunning($id){
	  //get the IP of the device
	  $computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

    $isRunning = $result = ['transmitted'=>1,'received'=>0];
    if($computer)
    {
	     $isRunning = $this->Ping->ping($computer['IPaddress']);
    }

    $this->set('result', $isRunning);
    $this->render('json');
	}

  function newLicenseKey($license_id){
    $this->viewBuilder()->setLayout('fancybox');

    $this->set('license_id', $license_id);
	}

  function setupCommand($id){
    $this->viewBuilder()->setLayout('fancybox');

		//get the command that goes with this id
		$command = $this->fetchTable('Command')->find('all', ['conditions'=>['Command.id'=>$id]])->first();
		$this->set('command',$command);
	}

  function wol(){
    $this->Ping->wol($_SERVER['SERVER_ADDR'], $this->request->getQuery('mac'));

    $this->set('result', ['success']);
    $this->render('json');
	}
}
?>
