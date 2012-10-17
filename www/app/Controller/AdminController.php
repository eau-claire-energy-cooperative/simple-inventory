<?php
	
class AdminController extends AppController {
	var $uses = array('Logs','Location','Setting','User');
	var $helpers = array('Html','Session','Time','Form');
	var $paginate = array('limit'=>100, order=>array('Logs.id'=>'desc'));
	
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
	
	function index(){
		$this->set('title_for_layout','Admin');
	}
	
	public function logs()
	{
	 	$this->set('title_for_layout','Logs');
	 	$this->set('logs',$this->paginate('Logs'));
	}
	
	public function settings(){
		if($this->request->is('post'))
		{
			foreach(array_keys($this->data['Setting']) as $key)
			{
				$this->Setting->query(sprintf('update settings set settings.value = "%s" where settings.key = "%s"',$this->data['Setting'][$key],$key));
			}
		}
		
		$this->set('title_for_layout','Settings');
		$this->set('settings',$this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value'))));
	}
	
	public function settings2($delete = null){
		
		if(isset($delete))
		{
			//delete the id given
			$id = $this->params['url']['id'];
			$this->Setting->delete($id);
			$this->Session->setFlash('Your entry has been deleted.');
			
		}
		
		$this->set('title_for_layout','Settings');
		$this->set('settings',$this->Setting->find('all',array('order'=>array('Setting.key'))));
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
            	$this->Session->setFlash('Your entry has been updated.');
            	$this->redirect(array('action' => 'settings'));
        	} 
        	else 
        	{
            	$this->Session->setFlash('Unable to update your entry.');
        	}	
		}
	}
	
	public function location() {
	 	$this->set('title_for_layout','Locations');
        $this->set('location', $this->Location->find('all', array('order'=> array('is_default desc, location ASC'))));// gets all data
    }
	
	public function editLocation($id= null) {
		$this->set('title_for_layout','Edit Location');
    	$this->Location->id = $id;
    	
    	if ($this->request->is('get')) {
        	$this->request->data = $this->Location->read();
   		} 
   		else 
   		{
        	if ($this->Location->save($this->request->data)) {
            	$this->Session->setFlash('Your entry has been updated.');
            	$this->redirect(array('action' => 'location'));
        	} 
        	else 
        	{
            	$this->Session->setFlash('Unable to update your entry.');
        	}
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
		$this->set('title_for_layout','Add Location');
		
        if ($this->request->is('post')) {
            if ($this->Location->save($this->request->data)) {
                $this->Session->setFlash('Your Entry has been saved.');
                $this->redirect(array('action' => 'location'));
            } else {
                $this->Session->setFlash('Unable to add your Entry.');
            }
        }
    }
    
    public function deleteLocation($id) {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }
	    if ($this->Location->delete($id)) {
	        $this->Session->setFlash('The entry with id: ' . $id . ' has been deleted.');
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
    			$this->Session->setFlash("Your entry has been deleted");
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
            	$this->Session->setFlash('Your entry has been updated.');
            	$this->redirect(array('action' => 'users'));
        	} 
        	else 
        	{
            	$this->Session->setFlash('Unable to update your entry.');
        	}
   		}
	}
	
}

?>