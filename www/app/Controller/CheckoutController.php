<?php

class CheckoutController extends AppController {
  var $components = array('Session');
  var $helpers = array('Html', 'Form', 'Session');
  var $uses = array("Setting", "CheckoutDeviceType");
  var $layout = 'login';

  public function beforeFilter(){

    if($this->action != 'disabled')
    {
      //make sure checkout is allowed
      $checkoutEnabled = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'enable_device_checkout')));

      if(!isset($checkoutEnabled) || trim($checkoutEnabled['Setting']['value']) != 'true')
      {
        $this->redirect(array('action'=>'disabled'));
      }
    }
  }

  function index(){
    $this->set('title_for_layout','Equipment Checkout Request');

    // get list of available devices by type
    $devices = $this->CheckoutDeviceType->find('all', array('order'=>'CheckoutDeviceType.name asc'));

    // only list types that have devices available
    $available = array();
    foreach($devices as $d)
    {
      if(count($d['Computer']) > 0)
      {
        $available[$d['CheckoutDeviceType']['id']] = $d['CheckoutDeviceType']['name'] . " - " . count($d['Computer']) . " available";
      }
    }

    $this->set('available', $available);
  }

  function disabled(){
    $this->set('title_for_layout','Equipment Checkout Request');
  }

  function submit(){

    if(empty($this->data['Checkout']['employee_name']) || empty($this->data['Checkout']['employee_email']))
    {
      $this->Flash->error('Name and email are required');
    }
    else if(empty($this->data['Checkout']['devices']))
    {
      $this->Flash->error("You must select at least one type of device");
    }
    else
    {
      $this->Flash->success('Request Submitted');
    }

    $this->render('index');
  }
}
?>
