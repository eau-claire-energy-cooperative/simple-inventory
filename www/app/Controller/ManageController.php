<?php

class ManageController extends AppController {
	var $uses = array('Computer','License','Logs','Location','Setting','User','Command','Schedule','Programs','RestrictedProgram');
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

	function restricted_programs(){
	    $this->set('title_for_layout','Programs');

	    //get a list of all programs on the system
	    $all_programs = $this->Programs->find('all',array('fields'=>array('DISTINCT Programs.program'),'order'=>array('Programs.program')));
	    $this->set('all_programs',$all_programs);

	    //get a list of currently restricted programs
	    $this->set('restricted_programs',$this->RestrictedProgram->find('list',array('fields'=>array('RestrictedProgram.name','RestrictedProgram.id'))));
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
