<?php

class ManageController extends AppController {
	var $uses = array('Computer','DeviceType','License','LicenseKey','Logs','Location','Setting','User','Command','Schedule');
	var $helpers = array('Html','Markdown','Session','Time','Form','License','LogParser');
	var $paginate = array('limit'=>100, 'order'=>array('Logs.id'=>'desc'));

	public function beforeFilter(){
	  $this->_check_authenticated();
	}

	public function beforeRender(){
	    parent::beforeRender();
	    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
	    $this->set('settings',$settings);
	}

  function licenses(){
      $this->set('title_for_layout', 'Licenses');
      $this->set('active_menu', 'applications');

      //get a list of all licenses
      $licenses = $this->License->find('all', array('order'=>array('License.LicenseName asc')));
      $this->set('licenses', $licenses);

  }

  function view_license($id){
      $this->set('title_for_layout', 'License Detail');
      $this->set('active_menu', 'applications');

      if($this->request->is('post')){

          if(isset($this->data['MoveLicense']))
          {
              //compare quantity to make sure a license is available
              $license_key = $this->LicenseKey->find('first', array('conditions'=>array('LicenseKey.id'=>$this->data['MoveLicense']['license_key_id'])));

              if(count($license_key['Computer']) < $license_key['LicenseKey']['Quantity'])
              {
                $this->LicenseKey->query('insert into computer_license (device_id, license_id) values (' . $this->data['MoveLicense']['computer'] . ',' . $this->data['MoveLicense']['license_key_id'] . ')');

                $this->Flash->success('License Assigned');
              }
              else
              {
                $this->Flash->error('No more keys available');
              }
          }
          else
	        {
              if($this->data['LicenseKey']['Quantity'] > 0)
              {
	               $this->LicenseKey->save($this->data['LicenseKey']);
	               $this->Flash->success('License Key Added');
              }
              else
              {
                $this->Flash->error('Quantity must be greater than 0');
              }
	        }
      }

      //get the license to display
      $license = $this->License->find('first', array('conditions'=>array('License.id' => $id), 'recursive'=>2));

      $this->set('license', $license);

  }

  function edit_license($id=NULL){
    $this->set('title_for_layout', 'Edit License');

    $this->License->id = $id;

    if($this->request->is('get'))
    {
        //attempt to read current license info
        $this->request->data = $this->License->read();
    }
    else
    {
      //check if expiration is set
      if($this->data['License']['NoExpiration'])
      {
        $this->request->data['License']['ExpirationDate'] = null;
      }
      //save the license
      $this->License->save($this->request->data);
      $this->Flash->success('License Saved');
      $this->redirect("/manage/view_license/" . $this->License->id);
    }
  }

  function delete_license($id){
      $license = $this->License->find('first', array('conditions'=>array('License.id'=>$id)));

      // can't delete a license with active keys
      if(count($license['LicenseKey']) == 0)
      {
        if ($this->License->delete($id)) {
          $this->Flash->success('License Deleted');
        }
        else
        {
          $this->Flash->error('Error deleting license');
        }
        $this->redirect(array('action' => 'licenses'));
      }
      else
      {
        $this->Flash->error('Cannot delete a license with active keys');
        $this->redirect('/manage/view_license/' . $id);
      }

	}

  function reset_license($link_id){

    // delete the link that joins the license to the device
    $this->License->query('delete from computer_license where id=' . $link_id);
    $this->Flash->success("License Removed");

    // redirect back to the original URL
    $this->redirect($this->request->referer(true));
  }

	function delete_license_key($license_id, $license_key_id){
    $license_key = $this->LicenseKey->find('first', array('conditions'=>array('LicenseKey.id'=>$license_key_id)));

    // cannot delete key with devices assigned
    if(count($license_key['Computer']) == 0)
    {

	    if ($this->LicenseKey->delete($id)) {
	        $this->Flash->success('License Key Deleted');
	    }
      else
      {
        $this->Flash->error('Error deleting license key');
      }

    }
    else
    {
      $this->Flash->error('Cannot delete key with assigned devices');
    }

    $this->redirect('/manage/view_license/' . $license_id);
	}

  public function deviceTypes() {
    $this->set('title_for_layout','Device Types');
        $this->set('device_types', $this->DeviceType->find('all', array('order'=> array('name ASC'))));
  }

  public function addDeviceType() {
    $this->set('title_for_layout','Add Device Type');
    $this->set('allowedAttributes', array_merge($this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    if ($this->request->is('post')) {
        $this->request->data['DeviceType']['attributes'] = implode(",",$this->request->data['DeviceType']['attributes']);

        if ($this->DeviceType->save($this->request->data)) {
            $this->Flash->success('Your Entry has been saved.');
            $this->redirect(array('action' => 'deviceTypes'));
        } else {
            $this->Flash->error('Unable to add your Entry.');
        }
    }
    }

    public function deleteDeviceType($id) {

      if ($this->DeviceType->delete($id)) {
          $this->Flash->success('The entry with id: ' . $id . ' has been deleted.');
          $this->redirect(array('action' => 'deviceTypes'));
      }
  }

  public function editDeviceType($id= null) {
    $this->set('title_for_layout','Edit Device Type');
    $this->DeviceType->id = $id;
    $this->set('allowedAttributes', array_merge($this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    if ($this->request->is('get')) {
        $this->request->data = $this->DeviceType->read();
    }
    else
    {
        $this->request->data['DeviceType']['attributes'] = implode(",",$this->request->data['DeviceType']['attributes']);

        if ($this->DeviceType->save($this->request->data)) {
            $this->Flash->success('Your entry has been updated.');
            $this->redirect(array('action' => 'deviceTypes'));
        }
        else
        {
            $this->Flash->error('Unable to update your entry.');
        }

        $this->redirect('/admin/deviceTypes');
    }
  }

	function commands(){
	    $this->set('active_menu', 'schedule');
	    $this->set('title_for_layout','Scheduled Tasks');

	    //get all of the commands that can be scheduled
	    $all_commands = $this->Command->find('all',array('order'=>array('Command.name')));
	    $this->set('all_commands',$all_commands);

	    //get all of the current schedules
	    $all_schedules = $this->Schedule->find('all',array('order'=>array('Command.name')));
	    $this->set('all_schedules',$all_schedules);
	}

	function schedule($id = NULL){

	    if($this->request->is('post'))
	    {
	        #setup the schedule model
	        $this->Schedule->create();
	        $this->Schedule->set('schedule',$this->data['Schedule']['schedule']);
	        $this->Schedule->set('command_id',$this->data['Schedule']['command_id']);

	        //get all of the parameters
	        $schedule_params = 'array(';
	        if($this->data['Schedule']['parameter_list'] != '')
	        {
	            $parameters = explode(',',$this->data['Schedule']['parameter_list']);

	            foreach($parameters as $param){
	                $schedule_params = $schedule_params . "'" . $param . "'=>'" . $this->data['Schedule']['param_' . $param] . "',";
	            }

	            $schedule_params = substr($schedule_params,0,-1);
	        }

	        $schedule_params = $schedule_params . ')';
	        $this->Schedule->set('parameters',$schedule_params);
	        $this->Schedule->save();

	        $this->Flash->success('Schedule Created');
	    }
	    else
	    {
	        if($id != NULL)
	        {
	            $this->Schedule->delete($id);

	            $this->Flash->success('Schedule Removed');
	        }
	    }

	    $this->redirect(array('action'=>'commands'));
	}

} ?>
