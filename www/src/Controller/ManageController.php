<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class ManageController extends AppController {

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

  public function deviceTypes() {
    $this->set('title','Device Types');
    $this->set('device_types', $this->fetchTable('DeviceType')->find('all', ['contain'=>['Computer'],
                                                                             'order'=>['name'=>'ASC']])->all());
  }

}
