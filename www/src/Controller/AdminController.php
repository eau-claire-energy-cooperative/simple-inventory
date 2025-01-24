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

  public function editSetting($id = null){
		$this->set('title','Add Setting');

    $Setting = $this->fetchTable('Setting');

    if($this->request->is('get'))
		{
			if(isset($id))
			{
				//get the information about this id
				$this->set('title','Edit Setting');
				$this->set('setting', $Setting->get($id));
			}
      else{
        $this->set('setting', $Setting->newEmptyEntity());
      }
		}
		else
		{
      $setting = $Setting->newEntity($this->request->getData());

			if ($Setting->save($setting)) {
        $this->Flash->success('Setting saved');

        return $this->redirect(array('action' => 'settings'));
    	}
    	else
    	{
        $this->Flash->error('Unable to update the setting');
    	}
		}
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

  public function settings2($delete = null){
		$this->set('title', 'Advanced Settings');

    $Setting = $this->fetchTable('Setting');
		if(isset($delete))
		{
			//delete the id given
      $setting = $Setting->get($this->request->getQuery('id'));
			$Setting->delete($setting);

			$this->Flash->success('Setting deleted');

		}

		$this->set('settings_list', $Setting->find('all', ['order'=>['Setting.key']])->all());
	}
}
?>
