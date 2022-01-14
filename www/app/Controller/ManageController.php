<?php

class ManageController extends AppController {
	var $uses = array('Computer','DeviceType','License','Logs','Location','Setting','User','Command','Schedule','Programs','RestrictedProgram');
	var $helpers = array('Html','Session','Time','Form','LogParser');
	var $paginate = array('limit'=>100, 'order'=>array('Logs.id'=>'desc'));

	public function beforeFilter(){
	    //check if we are using a login method
	    if(!$this->Session->check('authenticated')){
	        //check if we are using a login method
	        $loginMethod = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'auth_type')));

	        if(isset($loginMethod) && trim($loginMethod['Setting']['value']) == 'none')
	        {
	            //we aren't authenticating, just keep moving
	            $this->Session->write('authenticated','true');
	        }
	        //check, we may already be trying to go to the login page
	        else if($this->action != 'login')
	        {
	            //we need to forward to the login page
	            $this->redirect(array('controller'=>'inventory','action'=>'login'));
	        }
	    }
	}

	public function beforeRender(){
	    parent::beforeRender();
	    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
	    $this->set('settings',$settings);
	}

	function licenses(){
	    $this->set('title_for_layout', 'Program Licenses');
      $this->set('active_menu', 'applications');

	    if($this->request->is('post')){

	        if(isset($this->data['MoveLicense']))
	        {
	            $this->License->query('update licenses set comp_id = ' . $this->data['MoveLicense']['computer'] . ' where id=' . $this->data['MoveLicense']['license_id']);

	            $this->Flash->success('License Moved');
	        }
	        else
	        {
	            $this->License->save($this->data['License']);
	            $this->Flash->success('License Added');
	        }
	    }

	    //get a list of all licenses
	    $licenses = $this->License->find('all', array('order'=>array('Computer.ComputerName asc', 'License.ProgramName asc')));
	    $this->set('licenses', $licenses);

	}

	function deleteLicense($id){

	    if ($this->License->delete($id)) {
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

  function add_program(){
    $this->set('title_for_layout', 'Add Program');

    $allComputers = $this->Computer->find('list',array('fields'=>array('Computer.id', 'Computer.ComputerName'), 'order'=>array('Computer.ComputerName asc')));
    $this->set('computers', $allComputers);
  }

	function restricted_programs(){
	    $this->set('title_for_layout','Programs');

      if($this->request->is('post')){
        $this->Programs->save($this->request->data);
        $this->Flash->success('Program saved successfully');
      }

	    //get a list of all programs on the system
	    $all_programs = $this->Programs->find('all',array('fields'=>array('DISTINCT Programs.program', 'Programs.version'), 'group'=>array('Programs.program', 'Programs.version'), 'order'=>array('Programs.program', 'Programs.version desc')));
	    $this->set('all_programs',$all_programs);

	    //get a list of currently restricted programs
	    $this->set('restricted_programs',$this->RestrictedProgram->find('list',array('fields'=>array('RestrictedProgram.name','RestrictedProgram.id'))));
	}

  function unassign_program($program_id, $comp_id){
    // delete this entry from the programs DB
    $this->Programs->delete($program_id);

    $this->Flash->success('Program removed');

    $this->redirect('/inventory/moreInfo/' . $comp_id);
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
