<?php
	
class AdminController extends AppController {
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
	
	function beforeRender(){
	    parent::beforeRender();
	    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
	    $this->set('settings',$settings);
	}
	
	function index(){
		$this->set('title_for_layout','Admin');
	}
	
	public function logs()
	{
	 	$this->set('title_for_layout','Logs');
	 	$this->set('logs',$this->paginate('Logs'));
		$this->set('inventory',$this->Computer->find('list',array('fields'=>array('Computer.ComputerName','Computer.id'))));
	}
	
	public function settings($action = null){
		if($this->request->is('post'))
		{
			foreach(array_keys($this->data['Setting']) as $key)
			{
				//if setting is array, make it a string
				$value = $this->data['Setting'][$key];
				if(is_array($this->data['Setting'][$key]))
				{
					$value = implode(",",$this->data['Setting'][$key]);
				}

				$this->Setting->query(sprintf('update settings set settings.value = "%s" where settings.key = "%s"',$value,$key));
			}
			
			$this->Flash->success('Settings Saved');
			
		}
		
		$this->set('title_for_layout','Settings');

	}
	
	public function settings2($delete = null){
		
		if(isset($delete))
		{
			//delete the id given
			$id = $this->params['url']['id'];
			$this->Setting->delete($id);
			$this->Flash->success('Setting deleted');
			
		}
		
		$this->set('title_for_layout','Settings');
		$this->set('settings_list',$this->Setting->find('all',array('order'=>array('Setting.key'))));
	}
	
	
	public function edit_setting($id = null){
		$this->set('title_for_layout','Add Setting');
		
		if($this->request->is('get'))
		{
			if(isset($id))
			{
				//get the information about this id
				$this->set('title_for_layout','Edit Setting');
				$this->set('setting',$this->Setting->find('first',array('conditions'=>array('Setting.id'=>$id))));
			}
		}
		else 
		{
			if ($this->Setting->save($this->request->data)) {
            	$this->Flash->success('Setting saved');
            	$this->redirect(array('action' => 'settings'));
        	} 
        	else 
        	{
            	$this->Flash->error('Unable to update the setting');
        	}	
		}
	}
	
	public function location() {
	 	$this->set('title_for_layout','Locations');
        $this->set('location', $this->Location->find('all', array('order'=> array('is_default desc, location ASC'))));// gets all data
    }
	
	public function editLocation($id= null) {
		$this->set('title_for_layout','Edit Tag');
    	$this->Location->id = $id;
    	
    	if ($this->request->is('get')) {
        	$this->request->data = $this->Location->read();
   		} 
   		else 
   		{
        	if ($this->Location->save($this->request->data)) {
            	$this->Flash->success('Your entry has been updated.');
            	$this->redirect(array('action' => 'location'));
        	} 
        	else 
        	{
            	$this->Flash->error('Unable to update your entry.');
        	}
        	
        	$this->redirect('/admin/location');
   		}
	}
	
	public function setDefaultLocation($id){
		//reset all locations to false
		$this->Location->query("update location set is_default='false'");	
		
		$this->Location->create();
		$this->Location->set('id',$id);
		$this->Location->set('is_default','true');
		$this->Location->save();
		
		$this->redirect(array('action'=>'location'));
	}
	
	public function addLocation() {
		$this->set('title_for_layout','Add Tag');
		
        if ($this->request->is('post')) {
            if ($this->Location->save($this->request->data)) {
                $this->Flash->success('Your Entry has been saved.');
                $this->redirect(array('action' => 'location'));
            } else {
                $this->Flash->error('Unable to add your Entry.');
            }
        }
    }
    
    public function deleteLocation($id) {
	   
	    if ($this->Location->delete($id)) {
	        $this->Flash->success('The entry with id: ' . $id . ' has been deleted.');
	        $this->redirect(array('action' => 'location'));
	    }
	}
	
	public function users(){
		$this->set('title_for_layout','Users');
		
		$users = $this->User->find('all',array('order'=>array('User.name')));
		$this->set('users',$users);
	}
	
	public function editUser($id= null) {
		$this->set('title_for_layout','Edit User');
    	$this->User->id = $id;
    	
    	if ($this->request->is('get')) {
    			
    		if(isset($this->params['url']['action']) && $this->params['url']['action'] == 'delete'){
    			$this->User->delete($id);
    			$this->Flash->success("Your entry has been deleted");
    			$this->redirect(array('action'=>'users'));
    		}
			else
			{
        		$this->request->data = $this->User->read();
			}
   		} 
   		else 
   		{
   			//hash the password - if needed
   			if(!isset($this->request->data['User']['password_original']) || 
   			(isset($this->request->data['User']['password_original']) && $this->request->data['User']['password_original'] != $this->request->data['User']['password']))
   			{
   				$this->request->data['User']['password'] = md5($this->request->data['User']['password']);
			}
   			
        	if ($this->User->save($this->request->data)) {
            	$this->Flash->success('Your entry has been updated.');
            	$this->redirect(array('action' => 'users'));
        	} 
        	else 
        	{
            	$this->Flash->error('Unable to update your entry.');
        	}
   		}
	}
}

?>