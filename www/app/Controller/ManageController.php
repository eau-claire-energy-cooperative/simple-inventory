<?php

class ManageController extends AppController {
	var $uses = array('Computer','DeviceType','LicenseKey','Logs','Location','Setting','User','Command','Schedule');
	var $helpers = array('Html','Session','Time','Form','LogParser');
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

	    if($this->request->is('post')){

	        if(isset($this->data['MoveLicense']))
	        {
	            $this->LicenseKey->query('update license_keys set comp_id = ' . $this->data['MoveLicense']['computer'] . ' where id=' . $this->data['MoveLicense']['license_id']);

	            $this->Flash->success('License Moved');
	        }
	        else
	        {
	            $this->LicenseKey->save($this->data['LicenseKey']);
	            $this->Flash->success('License Added');
	        }
	    }

	    //get a list of all licenses
	    $licenses = $this->LicenseKey->find('all', array('order'=>array('Computer.ComputerName asc', 'LicenseKey.ProgramName asc')));
	    $this->set('licenses', $licenses);

	}

  function reset_license($license_id, $computer_id){

    // set the license to "no computer"
    $this->License->query('update license_keys set comp_id = 0 where id=' . $license_id);
    $this->Flash->success("License Removed");
    $this->redirect('/inventory/moreInfo/' . $computer_id);
  }

	function deleteLicense($id){

	    if ($this->LicenseKey->delete($id)) {
	        $this->Flash->success('License Deleted');
	        $this->redirect(array('action' => 'licenses'));
	    }
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
