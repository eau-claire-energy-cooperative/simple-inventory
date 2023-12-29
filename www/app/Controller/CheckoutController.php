<?php

App::uses('CakeTime', 'Utility');
class CheckoutController extends AppController {
  var $components = array('Session');
  var $helpers = array('Html', 'Form', 'Session');
  var $uses = array("Setting", "Computer", "CheckoutRequest", "DeviceType");
  var $layout = 'default';

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
      $checkOut = $this->data['CheckoutRequest']['check_out_date'];
      $checkIn = $this->data['CheckoutRequest']['check_in_date'];

      if(empty($this->data['CheckoutRequest']['employee_name']) || empty($this->data['CheckoutRequest']['employee_email']))
      {
        $this->Flash->error('Name and email are required');
      }
      else if(strtotime(sprintf("%s-%s-%s", $checkOut['year'], $checkOut['month'],$checkOut['day'])) < time()){
        $this->Flash->error("Check Out Date has passed");
      }
      else if(strtotime(sprintf("%s-%s-%s", $checkIn['year'], $checkIn['month'],$checkIn['day'])) < strtotime(sprintf("%s-%s-%s", $checkOut['year'], $checkOut['month'],$checkOut['day'])))
      {
        $this->Flash->error("Check In Date is before Check Out Date");
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

    //don't show menu if not logged in
    if(!$this->Session->check('authenticated'))
    {
      $this->layout = 'login';
    }

    //modify join to only pull in devices that can be checked out
    $this->DeviceType->unbindModel(
        array('hasMany' => array('Computer'))
    );
    $this->DeviceType->bindModel(array(
      'hasMany'=>array(
        'Computer' => array(
            'foreignKey' => 'DeviceType',
            'conditions' => array('CanCheckout'=>'true')
        )
      )
    ));

    // get list of available devices by type
    $devices = $this->DeviceType->find('all', array('order'=>'DeviceType.name asc'));

    // only list types that have devices available
    $available = array();
    foreach($devices as $d)
    {
      if(count($d['Computer']) > 0)
      {
        $available[$d['DeviceType']['id']] = $d['DeviceType']['name'] . " - " . count($d['Computer']) . " available";
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

    if($req['CheckoutRequest']['status'] != 'approved')
    {

      //modify join to only pull in devices that can be checked out
      $this->DeviceType->unbindModel(
          array('hasMany' => array('Computer'))
      );
      $this->DeviceType->bindModel(array(
        'hasMany'=>array(
          'Computer' => array(
              'foreignKey' => 'DeviceType',
              'conditions' => array('CanCheckout'=>'true')
          )
        )
      ));

      //find an available device
      $found_device = -1;
      $devices = $this->DeviceType->find('first', array('conditions'=>array('DeviceType.id'=>$req['CheckoutRequest']['device_type']),
                                                        'recursive'=>2));

      foreach($devices['Computer'] as $d)
      {
        //check if this device will be available
        if($this->_checkAvailable($req['CheckoutRequest']['check_out_unix'], $req['CheckoutRequest']['check_in_unix'], $d['CheckoutRequest']))
        {
          $found_device = $d['id'];
          break;
        }
      }

      if($found_device > 0)
      {
        $checkOutDate = CakeTime::format($req['CheckoutRequest']['check_out_date'], '%m/%d/%Y');
        $checkInDate = CakeTime::format($req['CheckoutRequest']['check_in_date'], '%m/%d/%Y');

        //approve the request
        $req['CheckoutRequest']['status'] = 'approved';
        $this->CheckoutRequest->save($req);

        $this->CheckoutRequest->query("insert into checkout_reservation (request_id, device_id) values (" . $id . ", " . $found_device . ")");

        // send email to user
        $this->_send_email("Equipment Checkout Approved",
                           sprintf("Your equipment checkout request from %s to %s has been approved.", $checkOutDate, $checkInDate),
                           $req['CheckoutRequest']['employee_email']);

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

    if($req['CheckoutRequest']['status'] != 'active')
    {
      $checkOutDate = CakeTime::format($req['CheckoutRequest']['check_out_date'], '%m/%d/%Y');
      $checkInDate = CakeTime::format($req['CheckoutRequest']['check_in_date'], '%m/%d/%Y');

      //deny the request
      $req['CheckoutRequest']['status'] = 'denied';
      $this->CheckoutRequest->save($req);

      if(count($req['Computer']) > 0){
        $this->CheckoutRequest->query("delete from checkout_reservation where request_id = " . $id . " and device_id = " . $req['Computer'][0]['id']);
      }

      //notify the user
      $this->_send_email("Equipment Checkout Denied",
                         sprintf("Your equipment checkout request from %s to %s has been approved. The most common reason for this is that the requested equipment is not available.", $checkOutDate, $checkInDate),
                         $req['CheckoutRequest']['employee_email']);

      $this->Flash->success("Request Denied");
    }

    $this->redirect('/checkout/requests');
  }

  function device($action, $request_id, $device_id){
    $this->_check_authenticated();

    $request = $this->CheckoutRequest->find('first', array('conditions'=>array('CheckoutRequest.id'=>$request_id)));
    $device = $request['Computer'][0];

    //make sure request is approved and the device is not checked out
    if($request['CheckoutRequest']['status'] == 'approved' && $device['CanCheckout'] == 'true')
    {
      if($action == 'out')
      {
        if($device['IsCheckedOut'] == 'false')
        {
          //set the request to active
          $request['CheckoutRequest']['status'] = 'active';
          $this->CheckoutRequest->save($request);

          // update the device
          $device['IsCheckedOut'] = 'true';
          $this->Computer->save($device);

          $this->Flash->success($device['ComputerName'] . ' checked out');
        }
        else
        {
          $this->Flash->error($device['ComputerName'] . " is checked out already");
        }

      }
      else
      {
        //make sure this is the right request and the device is checked out
        if($request['CheckoutRequest']['status'] == 'active' && $device['IsCheckedOut'] == 'true')
        {

          //deactivate request
          $request['CheckoutRequest']['status'] = 'approved';
          $this->CheckoutRequest->save($request);

          // update the device
          $device['IsCheckedOut'] = 'false';
          $this->Computer->save($device);

          $this->Flash->success($device['ComputerName'] . ' is checked in');
        }
        else
        {
          $this->Flash->error($device['ComputerName'] . " is not checked out");
        }

      }
    }
    else
    {
      $this->Flash->error($device['ComputerName'] . ' is not available to check in or out');
    }

    $this->redirect('/checkout/requests');
  }

  function _checkAvailable($checkOut, $checkIn, $reservations){
    $result = true;  //assume there won't be any overlaps

    // see if any reservations overlap with the existing dates
    foreach($reservations as $r)
    {
      if($r['check_out_unix'] < $checkIn && $r['check_in_unix'] >= $checkOut)
      {
        $result = false;
      }
    }

    return $result;
  }

}
?>
