<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class AdminController extends AppController {

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

  function index(){
    $this->set('title', 'Admin');
  }
}
?>
