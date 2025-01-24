<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class AdminController extends AppController {
  public $paginate = [
      'limit' => 50
  ];

  public function initialize(): void
  {
    parent::initialize();
  }

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    // find settings before rendering
    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
    $this->set("settings", $settings);
  }

  function downloads(){
		$this->set('title','Downloads');
	}

  function index(){
    $this->set('title', 'Admin');
  }

  public function logs()	{
	 	$this->set('title','Logs');
    $this->viewBuilder()->addHelper('LogParser');

    $logs = $this->fetchTable('Logs')->find('all', ['order'=>['Logs.id'=>'desc']]);
	 	$this->set('logs',$this->paginate($logs));

		$this->set('inventory', $this->fetchTable('Computer')->find('list', ['keyField'=>'ComputerName', 'valueField'=>'id'])->toArray());
	}
}
?>
