<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;

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

  function approve($id){
    $this->_check_authenticated();

    //check if this reservation is already approved
    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $req = $CheckoutRequest->find('all', ['conditions'=>['CheckoutRequest.id'=>$id]])->first();

    if($req['status'] != 'approved')
    {
      //find an available device
      $found_device = -1;
      $devices = $this->fetchTable('DeviceType')->find('all', ['contain'=>['ComputerCheckout', 'ComputerCheckout.CheckoutRequest'],
                                                               'conditions'=>['DeviceType.id'=>$req['device_type']]])->first();

      foreach($devices['computer_checkout'] as $d)
      {
        //check if this device will be available
        if($this->_checkAvailable($req, $d['checkout_request']))
        {
          $found_device = $d['id'];
          break;
        }
      }

      if($found_device > 0)
      {
        $checkOutDate = $req['check_out_date']->i18nFormat('MM/dd/yyy');
        $checkInDate = $req['check_in_date']->i18nFormat('MM/dd/yyy');

        //approve the request
        $req['status'] = 'approved';
        $CheckoutRequest->save($req);

        $this->fetchTable('CheckoutReservation')->insertQuery()->insert(['request_id', 'device_id'])
                                                               ->values(['request_id'=>$id, 'device_id'=>$found_device])->execute();

        // send email to user
        $this->_send_email("Device Checkout Approved",
                           sprintf("Your equipment checkout request from %s to %s has been approved. See %s to pick up your equipment.", $checkOutDate, $checkInDate, $this->request->getSession()->read('User.name')),
                           $req['employee_email']);

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

    return $this->redirect('/checkout/requests');
  }

  function delete($id){
    $this->_check_authenticated();

    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $req = $CheckoutRequest->find('all', ['conditions'=>['CheckoutRequest.id'=>$id]])->first();

    if($req['status'] == 'denied' || $req['check_in_date']->isPast())
    {
      $CheckoutRequest->delete($req);
      $this->Flash->success("Request Deleted");
    }
    else
    {
      $this->Flash->error("Request must be denied first");
    }

    return $this->redirect('/checkout/requests');
  }

  function device($action, $request_id, $device_id){
    $this->_check_authenticated();

    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $request = $CheckoutRequest->find('all', ['contain'=>['Computer'],
                                              'conditions'=>['CheckoutRequest.id'=>$request_id]])->first();
    $device = $request['computer'][0];

    //make sure request is approved and the device is not checked out
    if($request['status'] != 'denied' && $device['CanCheckout'] == 'true')
    {
      if($action == 'out')
      {
        $settings = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'device_checkout_location']])->first();

        if($device['IsCheckedOut'] == 'false')
        {
          //set the request to active
          $request['status'] = 'active';
          $CheckoutRequest->save($request);

          // save the old device location (joinData is the reservation table)
          $this->fetchTable('CheckoutReservation')->updateQuery()->set(['saved_device_location'=>$device['ComputerLocation']])
                                                                 ->where(['id'=>$device['_joinData']['id']])
                                                                 ->execute();

          // update the device
          $device['IsCheckedOut'] = 'true';
          $device['ComputerLocation'] = $settings['value'];
          $this->fetchTable('Computer')->save($device);

          $this->Flash->success(sprintf('%s checked out', $device['ComputerName']));
        }
        else
        {
          $this->Flash->error(sprintf("%s is checked out already", $device['ComputerName']));
        }

      }
      else
      {
        //make sure this is the right request and the device is checked out
        if($request['status'] == 'active' && $device['IsCheckedOut'] == 'true')
        {

          //deactivate request
          $request['status'] = 'approved';
          $CheckoutRequest->save($request);

          // update the device
          $device['IsCheckedOut'] = 'false';
          $device['ComputerLocation'] = $device['_joinData']['saved_device_location'];
          $this->fetchTable('Computer')->save($device);

          $this->Flash->success(sprintf('%s is checked in', $device['ComputerName']));
        }
        else
        {
          $this->Flash->error(sprintf("%s is not checked out", $device['ComputerName']));
        }

      }
    }
    else
    {
      $this->Flash->error(sprintf('%s is not available to check in or out', $device['ComputerName']));
    }

    return $this->redirect('/checkout/requests');
  }

  function deny($id){
    $this->_check_authenticated();

    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $req = $CheckoutRequest->find('all', ['contain'=>['Computer'],
                                          'conditions'=>['CheckoutRequest.id'=>$id]])->first();

    if($req['status'] != 'active')
    {
      $checkOutDate = $req['check_out_date']->i18nFormat('MM/dd/yyy');
      $checkInDate = $req['check_in_date']->i18nFormat('MM/dd/yyy');

      //deny the request
      $req['status'] = 'denied';
      $CheckoutRequest->save($req);

      if(count($req['computer']) > 0){
        $this->fetchTable('CheckoutReservation')->deleteQuery()->where(['request_id'=>$id, 'device_id'=>$req['computer'][0]['id']])->execute();
      }

      //notify the user
      $this->_send_email("Device Checkout Denied",
                         sprintf("Your device checkout request from %s to %s has been denied. The most common reason for this is that the requested device is not available.", $checkOutDate, $checkInDate),
                         $req['employee_email']);

      $this->Flash->success("Request Denied");
    }

    return $this->redirect('/checkout/requests');
  }

  function disabled(){
    $this->set('title','Device Checkout Request');
    $this->viewBuilder()->setLayout('login');
  }

  function extend(){
    $this->_check_authenticated();

    if ($this->request->is('post'))
    {
      $now = FrozenTime::now();
      $CheckoutRequest = $this->fetchTable('CheckoutRequest');

      //pull in requests for this device ID
      $upcoming_requests = $this->fetchTable('Computer')->find('all', ['contain'=>['CheckoutRequest'],
                                                                       'conditions'=>['Computer.id'=>$this->request->getData('device_id')]])->first();

      // get request with NEW check in date
      $request = $CheckoutRequest->find('all', ['conditions'=>['CheckoutRequest.id'=>$this->request->getData('id')]])->first();
      $request->check_in_date = new FrozenTime(sprintf('%s 00:00:00', $this->request->getData('check_in_date')));

      if($request->check_in_date->greaterThanOrEquals($now) && $request->check_in_date->greaterThanOrEquals($request->check_out_date))
      {
        if($this->_checkAvailable($request, $upcoming_requests['checkout_request']))
        {
          if($CheckoutRequest->save($request))
          {
            $this->Flash->success("Check In Date extended");
          }
          else
          {
            $this->Flash->error("Error extending date");
          }
        }
        else {
          $this->Flash->error("Conflict - Check In Date cannot be extended");
        }
      }
      else
      {
        $this->Flash->error('Check In Date cannot be in the past');
      }
    }

    return $this->redirect('/checkout/requests');
  }

  function index(){
    $this->set('title','Device Checkout Request');

    $checkOut = FrozenTime::now();
    $checkIn = $checkOut->addWeeks(2);
    if($this->request->is('post'))
    {
      $now = FrozenTime::now();

      //set the dates, CakePHP has trouble parsing dates into timestamps
      $checkOut = new FrozenTime(sprintf('%s 23:59:00',$this->request->getData('check_out_date')));
      $checkIn = new FrozenTime(sprintf('%s 00:00:00',$this->request->getData('check_in_date')));

      if(empty($this->request->getData('employee_name')) || empty($this->request->getData('employee_email')))
      {
        $this->Flash->error('Name and email are required');
      }
      else if($checkOut->lessThan($now)){
        $this->Flash->error("Check Out Date has passed");
      }
      else if($checkIn->lessThan($checkOut))
      {
        $this->Flash->error("Check In Date is before Check Out Date");
      }
      else if(empty($this->request->getData('devices')))
      {
        $this->Flash->error("You must select at least one type of device");
      }
      else
      {
        $CheckoutRequest = $this->fetchTable('CheckoutRequest');

        // save each device request as it's own request
        foreach($this->request->getData('devices') as $d)
        {
          $checkout = $CheckoutRequest->newEmptyEntity();
          $checkout->employee_name = $this->request->getData('employee_name');
          $checkout->employee_email = $this->request->getData('employee_email');
          $checkout->check_out_date = $checkOut;
          $checkout->check_in_date = $checkIn;
          $checkout->device_type = $d;

          $CheckoutRequest->save($checkout);
        }

        // email an admin
        $this->_send_email("Device Checkout Request", sprintf("%s has submitted an equipment checkout request. Please review the request to approve or deny.", $this->request->getData('employee_name')));

        $this->Flash->success('Request Submitted');
      }
    }

    //don't show menu if not logged in
    if(!$this->request->getSession()->check('authenticated'))
    {
      $this->viewBuilder()->setLayout('login');
    }
    // get list of available devices by type
    $devices = $this->fetchTable('DeviceType')->find('all', ['contain'=>['ComputerCheckout'],
                                                             'order'=>'DeviceType.name asc'])->all();

    // only list types that have devices available
    $available = [];
    foreach($devices as $d)
    {
      if(count($d['computer_checkout']) > 0)
      {
        $available[$d['id']] = $d['name'];
      }
    }

    $this->set('available', $available);
    $this->set('checkOut', $checkOut);
    $this->set('checkIn', $checkIn);
    $this->set('authenticated', $this->request->getSession()->check('authenticated'));
  }

  function requests(){
    // check that we're logged in for this view
    $this->_check_authenticated();
    $this->set('title','Checkout Requests');

    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $active = $CheckoutRequest->find('all',  ['contain'=>['DeviceType', 'Computer'],
                                              'conditions'=>['CheckoutRequest.status'=>'active'], 'order'=>['CheckoutRequest.check_out_date']])->all();
    $this->set('active', $active);

    $new = $CheckoutRequest->find('all', ['contain'=>['DeviceType', 'Computer'],
                                          'conditions'=>['CheckoutRequest.status'=>'new'],
                                          'order'=>['CheckoutRequest.check_out_date']])->all();
    $this->set('new', $new);

    $upcoming = $CheckoutRequest->find('all', ['contain'=>['DeviceType', 'Computer'],
                                               'conditions'=>["CheckoutRequest.status not in ('new', 'active')"],
                                               'order'=>['CheckoutRequest.status', 'CheckoutRequest.check_out_date']])->all();
    $this->set('upcoming', $upcoming);

    // set the current date
    $this->set('now', FrozenTime::now());
  }

  function _checkAvailable($request, $reservations){
    $result = true;  //assume there won't be any overlaps

    $checkOut = $request['check_out_date'];
    $checkIn = $request['check_in_date'];

    // see if any reservations overlap with the existing dates
    foreach($reservations as $r)
    {
      // reservation must be active and not equal to the one being checked
      if(!in_array($r['status'], array('denied', 'new')) && $r['id'] != $request['id'])
      {
        if($r['check_out_date']->lessThan($checkIn) && $r['check_in_date']->greaterThanOrEquals($checkOut))
        {
          $result = false;
        }
      }
    }

    return $result;
  }
}
?>
