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

  function wol($host, $mac)
	{
    $this->Ping->wol($_SERVER['SERVER_ADDR'], $this->request->getQuery('mac'));

    $this->set('result', ['success']);
    $this->render('json');
	}
}
?>
