<?php
	
class InventoryController extends AppController {
    var $helpers = array('Html', 'Form', 'Session','Time','DiskSpace');
    var $components = array('Session','Ldap');

	public $uses = array('Computer','Location', 'Programs', 'Logs','Service','Decommissioned','Setting','User');

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
				$this->redirect(array('action'=>'login'));
			}
		}
	}
	
	function beforeRender(){
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		$this->set('settings',$settings);
	}

	public function index(){
		$this->redirect(array("action"=>"computerInventory"));
	}

	public function login(){
		$this->set('title_for_layout','Login');
		
		if ($this->request->is('post')) 
		{
			//check the type of login method
			$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));

			if($settings['auth_type'] == 'local')
			{
				//attempt to get a username that matches this password locally
				$aUser = $this->User->find('first',array('conditions'=>array('User.username'=>$this->data['User']['username'])));
				
				if($aUser)
				{
					//check the passwords
					if(md5($this->data['User']['password']) == $aUser['User']['password'])
					{
						//success!
						$this->Session->write('authenticated','true');
						$this->redirect('/');
					}
					else
					{
						$this->Session->setFlash('Incorrect Password');
					}
				}
				else
				{
					$this->Session->setFlash('Incorrect Username');
				}
			}
			else if($settings['auth_type'] == 'ldap')
			{
				
				//check if this user is allowed into the system (local user)
				$aUser = $this->User->find('first',array('conditions'=>array('User.username'=>$this->data['User']['username'])));
				
				if($aUser)
				{
					//use the ldap component to authorize the user, first set it up
					$this->Ldap->setup(array('host'=>$settings['ldap_host'],'port'=>$settings['ldap_port'],'baseDN'=>$settings['ldap_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']));
					
					if($this->Ldap->auth($this->data['User']['username'],$this->data['User']['password']))
					{
						//success!
						$this->Session->write('authenticated','true');
						$this->redirect('/');
					}
					else
					{
						$this->Session->setFlash('Incorrect Username/Password');
					}
				}
				else
				{
					$this->Session->setFlash('Incorrect Username/Password');
				}
			}
		}
	}
	
	public function logout(){
		//just destroy the session
		$this->Session->destroy();
		$this->redirect('/');
	}
	
	public function home()
	{
		$this->redirect(array('action' => 'computerInventory'));
	}
		
    public function computerInventory() {
    	$this->set('title_for_layout','Computer Inventory');
        $this->set('computer', $this->Computer->find('all', array('order'=> array('ComputerName ASC'))));// gets all data
    }
	
	
	 public function moreInfo( $id) {
	 	$this->set('title_for_layout','Computer Detail');
	 	
	 	$this->Computer->id = $id;
		$this->Programs->id = $id;
        $this->set('computer', $this->Computer->read());
		$this->set('programs', $this->Programs->find('all',array('conditions' => array('comp_id' => $id), 'order' => array('program ASC'))));
		$this->set('services', $this->Service->find('all',array('conditions' => array('comp_id' => $id), 'order' => array('name ASC'))));
		$this->set('logs', $this->Logs->find('all', array('order'=> array('id ASC'))));// gets all data
    }
    
	 public function moreInfoDecommissioned( $id) {
	 	$this->set('title_for_layout','Decommissioned Computer Detail');
	 	$this->Decommissioned->id = $id;
	
        $this->set('decommissioned', $this->Decommissioned->read());
    }
	
	public function add() {
		$this->set('title_for_layout','Add a New Computer');
		
		$this->set('location', $this->Location->find('list', array('fields' => array("Location.Location"), 'order'=>array('is_default desc, location asc'))));
        if ($this->request->is('post')) {
            if ($this->Computer->save($this->request->data)) {
            	//create log entry
            	$this->_saveLog("Computer " . $this->request->data['Computer']['ComputerName'] . " added to database");
            	
                $this->Session->setFlash('Your Entry has been saved.');
                $this->redirect(array('action' => 'computerInventory'));
            } else {
                $this->Session->setFlash('Unable to add your Entry.');
            }
        }
    }
	
	
	public function edit($id= null) {
		$this->set('title_for_layout','Edit Computer Data');
		$this->set('location', $this->Location->find('list', array('fields' => array("Location.Location"),'order'=>'Location.is_default desc, Location.Location asc')));
	    $this->Computer->id = $id;
	    
	    if ($this->request->is('get')) {
	        $this->request->data = $this->Computer->read();
	    } 
	    else 
	    {
	        if ($this->Computer->save($this->request->data)) {
	            $this->Session->setFlash('Your entry has been updated.');
	            $this->redirect(array('action' => 'computerInventory'));
	        } else {
	            $this->Session->setFlash('Unable to update your entry.');
	        	}
	   	}
	}

	public function delete($id) {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }
	    
	    //get the name of the computer for logging
	    $this->Computer->id = $id;
	    $computer = $this->Computer->read();
	    
	    if ($this->Computer->delete($id)) {
	    	$message = 'Computer ' . $computer['Computer']['ComputerName'] . ' has been deleted';
	    	
	    	$this->_saveLog($message);
	        $this->Session->setFlash($message);
	        $this->redirect(array('action' => 'computerInventory'));
	    }
		
	}
	
	
	
 	public function decommission() {
  		$this->set('title_for_layout','Decommissioned Computers');
        $this->set('decommission', $this->Decommissioned->find('all', array('order'=> array('LastUpdated ASC'))));// gets all data
    }
	

	public function confirmDecommission( $id = null)
	{
		$currID = $id; //variable to pass to transferDecom
		$this->Computer->id = $id;
    	
		if ($this->request->is('get')) {
        	$this->request->data = $this->Computer->read();
        	
        	$this->set('title_for_layout',"Decomission Process for " . $this->request->data['Computer']['ComputerName']);
    	} 
    	else 
    	{
        	if ($this->Computer->save($this->request->data)) 
        	{
        		$message = 'Computer ' . $this->request->data['Computer']['ComputerName'] . ' has been decommissioned';
        		$this->_saveLog($message);
            	$this->Session->setFlash($message);
       			$this->transferDecom($currID);
        	} 
        	else 
        	{
            	$this->Session->setFlash('Unable to update your entry.');
        	}
   		}
	}	

			
		
		
		public function transferDecom($id = null)
		{
			//get the computer model needed
			$comp = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));	

			$this->Decommissioned->create();
			$this->Decommissioned->set('ComputerName',$comp ['Computer']['ComputerName']);
			$this->Decommissioned->set('SerialNumber',$comp ['Computer']['SerialNumber']);
			$this->Decommissioned->set('AssetId',$comp ['Computer']['AssetId']);
			$this->Decommissioned->set('CurrentUser',$comp ['Computer']['CurrentUser']);
			$this->Decommissioned->set('Location',$comp ['Computer']['ComputerLocation']);
			$this->Decommissioned->set('Model',$comp ['Computer']['Model']);
			$this->Decommissioned->set('OS',$comp ['Computer']['OS']);
			$this->Decommissioned->set('Memory',$comp ['Computer']['Memory']);
			$this->Decommissioned->set('CPU',$comp ['Computer']['CPU']);
			$this->Decommissioned->set('NumberOfMonitors',$comp ['Computer']['NumberOfMonitors']);
			$this->Decommissioned->set('IPaddress',$comp ['Computer']['IPaddress']);
			$this->Decommissioned->set('MACaddress',$comp ['Computer']['MACaddress']);
			$this->Decommissioned->set('DiskSpace',$comp ['Computer']['DiskSpace']);
			$this->Decommissioned->set('LastUpdated',$comp ['Computer']['LastUpdated']);
			$this->Decommissioned->set('WipedHD',$comp ['Computer']['WipedHD']);
			$this->Decommissioned->set('Recycled',$comp ['Computer']['Recycled']);
			$this->Decommissioned->set('RedeployedAs',$comp ['Computer']['RedeployedAs']);
			$this->Decommissioned->set('notes',$comp ['Computer']['notes']);
			
			$this->Computer->delete($id);
		

			
		
			if( $this->Decommissioned->save())
			{
				$this->Session->setFlash("Machine with id: " . $id . " has been moved to the decommission table");
				$this->redirect(array("action" => 'computerInventory'));
			}
		}
		
	public function changeWipeStatus($id = null,$status)
	{
		$this->Decommissioned->id = $id;
		if($status == 'Yes')
		{
			$this->Decommissioned->set('WipedHD', 'Yes');
		}
		else 
		{
			$this->Decommissioned->set('WipedHD', 'No');	
		}
		if($this->Decommissioned->save())
		{
			$this->Session->setFlash('Wipe Hard Drive Status changed');
			$this->redirect(array('action' => 'decommission'));
		}
		else {
			{
				$this->Session->setFlash('Wipe Hard Drive Status failed to change');
			}
		}
	}
		
	public function changeRecycledStatus($id = null,$status)
	{
		$this->Decommissioned->id = $id;
		if($status == 'Yes')
		{
			$this->Decommissioned->set('Recycled', 'Yes');
		}
		else 
		{
			$this->Decommissioned->set('Recycled', 'No');	
		}
		if($this->Decommissioned->save())
		{
			$this->Session->setFlash('Recycled Status changed');
			$this->redirect(array('action' => 'decommission'));
		}
		else {
			{
				$this->Session->setFlash('Recycled Status failed to change');
			}
		}
	}
	
	function _saveLog($message){
		$this->Logs->create();
		$this->Logs->set('LOGGER','Website');
		$this->Logs->set('LEVEL','INFO');
		$this->Logs->set('MESSAGE',$message);
		$this->Logs->set("DATED",date("Y-m-d H:i:s",time()));
		$this->Logs->save();
	}
}
    
