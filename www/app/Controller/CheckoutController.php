<?php

class CheckoutController extends AppController {
  var $components = array('Session');
  var $helpers = array('Html', 'Form', 'Session');
  var $uses = array("Setting", "CheckoutDeviceType", "CheckoutRequest");
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

  public function beforeRender(){
    parent::beforeRender();
    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
    $this->set('settings',$settings);
	}

  function index(){
    $this->set('title_for_layout','Equipment Checkout Request');

    if($this->request->is('post'))
    {
      if(empty($this->data['CheckoutRequest']['employee_name']) || empty($this->data['CheckoutRequest']['employee_email']))
      {
        $this->Flash->error('Name and email are required');
      }
      else if(empty($this->data['CheckoutRequest']['devices']))
      {
        $this->Flash->error("You must select at least one type of device");
      }
      else
      {
        foreach($this->data['CheckoutRequest']['devices'] as $d)
        {
          $this->CheckoutRequest->create();

          $this->CheckoutRequest->set('employee_name', $this->data['CheckoutRequest']['employee_name']);
          $this->CheckoutRequest->set('employee_email', $this->data['CheckoutRequest']['employee_email']);
          $this->CheckoutRequest->set('check_out_date', $this->data['CheckoutRequest']['check_out_date']);
          $this->CheckoutRequest->set('check_in_date', $this->data['CheckoutRequest']['check_in_date']);
          $this->CheckoutRequest->set('device_type', $d);

          $this->CheckoutRequest->save();
        }

        $this->Flash->success('Request Submitted');
      }
    }

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

  function requests(){
    // check that we're logged in for this view
    $this->_check_authenticated();
    $this->set('title_for_layout','Checkout Requests');
    $this->layout = 'default';

    $checkout = $this->CheckoutRequest->find('all', array('order'=>array('CheckoutRequest.check_out_date')));
    $this->set('checkout', $checkout);
  }

  function approve($id){
    $this->_check_authenticated();

    //check if this reservation is already approved
    $req = $this->CheckoutRequest->find('first', array('conditions'=>array('CheckoutRequest.id'=>$id)));
    if(count($req['Computer']) == 0)
    {
      //find an available device
      $found_device = -1;
      $devices = $this->CheckoutDeviceType->find('first', array('conditions'=>array('CheckoutDeviceType.id'=>$req['CheckoutRequest']['device_type']),
                                                                'recursive'=>2));

      foreach($devices['Computer'] as $d)
      {
        if(count($d['CheckoutRequest']) == 0)
        {
          // break the loop here if we find one
          $found_device = $d['id'];
          break;
        }
      }

      if($found_device > 0)
      {
        $this->CheckoutRequest->query("insert into checkout_reservation (request_id, device_id) values (" . $id . ", " . $found_device . ")");
        $this->Flash->success("Request Approved");
      }
      else
      {
        $this->Flash->error("No Available Device For This Request");
      }
    }
    else
    {
      $this->Flash->error("Request Is Already Approved");
    }



    $this->redirect('/checkout/requests');
  }

  function deny($id){
    $this->_check_authenticated();

    $req = $this->CheckoutRequest->find('first', array('conditions'=>array('CheckoutRequest.id'=>$id)));

    if(count($req['Computer']) > 0){
      $this->CheckoutRequest->query("delete from checkout_reservation where request_id = " . $id . " and device_id = " . $req['Computer'][0]['id']);
    }

    $this->Flash->success("Request Denied");
    $this->redirect('/checkout/requests');
  }
}
?>
