<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class CheckoutController extends AppController {

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    if($this->request->getParam('action') != 'disabled')
    {
      //make sure checkout is allowed
      $checkoutEnabled = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'enable_device_checkout']])->first();

      if(!isset($checkoutEnabled) || trim($checkoutEnabled['value']) != 'true')
      {
        return $this->redirect(array('action'=>'disabled'));
      }
    }
  }

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    // find settings before rendering
    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
    $this->set("settings", $settings);
  }

  function disabled(){
    $this->set('title','Device Checkout Request');
    $this->viewBuilder()->setLayout('login');
  }
}
?>
