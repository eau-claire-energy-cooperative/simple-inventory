<?php
	
class InventoryController extends AppController {
    var $helpers = array('Html', 'Form', 'Session','Time','DiskSpace');
    var $components = array('Session','Ldap');

	public $uses = array('Computer','Location', 'Programs', 'Logs','Service','Decommissioned','Setting','User','RestrictedProgram');

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
		$this->set('restricted_programs',$this->RestrictedProgram->find('list',array('fields'=>array('RestrictedProgram.name','RestrictedProgram.id'))));
		
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
        	
			//trim computername
			$this->request->data['Computer']['ComputerName'] = trim($this->data['Computer']['ComputerName']); 
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
	            $this->redirect("/inventory/moreInfo/" . $this->data['Computer']['id']);
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
	    	//also delete programs and services
	    	$this->Programs->query('delete from programs where comp_id = ' . $id);
	    	$this->Service->query('delete from services where comp_id = ' . $id);
	    	
	    	$message = 'Computer ' . $computer['Computer']['ComputerName'] . ' has been deleted';
	    	
	    	$this->_saveLog($message);
	        $this->Session->setFlash($message);
	        $this->redirect(array('action' => 'computerInventory'));
	    }
		
		$this->redirect(array('action' => 'computerInventory'));
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
		
			//also delete programs and services 
			$this->Programs->query('delete from programs where comp_id = ' . $id);
			$this->Service->query('delete from services where comp_id = ' . $id);
			
		
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
	
	function active_directory_sync($action = null){
		$this->set('title_for_layout','Active Directory Sync');
		
		if($action != null)
		{
			$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
			//get the ldap computer listing
			$this->Ldap->setup(array('host'=>$settings['ldap_host'],'port'=>$settings['ldap_port'],'baseDN'=>$settings['ldap_computers_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']));
			$ad_computers = array();
			$ldap_response = $this->Ldap->getComputers();
			
			
			$result = array();
			
			if($action == 'compare')
			{
				foreach($ldap_response as $lr)
				{
					if(isset($lr['cn']))
					{
						$ad_computers[] = trim(strtoupper($lr['cn'][0]));
					}
				}
			
				//get the computer inventory 
				$inventory_computers = $this->Computer->find('list', array('fields'=>array('ComputerName'),'order'=> array('ComputerName ASC')));
			
				//transform the names to uppercase
				for($i = 0; $i < count($inventory_computers); $i ++)
				{
					$inventory_computers[$i] = trim(strtoupper($inventory_computers[$i]));
				}
			
				//find the differences between the two lists
				$ad_diff = array_diff($ad_computers,$inventory_computers);
				$inventory_diff = array_diff($inventory_computers,$ad_computers);
				
				//merge the lists
				foreach($ad_diff as $diff){
					$result[$diff] = array('value'=>'Not in Inventory','class'=>'not_inventory');
				}

				foreach($inventory_diff as $diff){
					if($diff != '')
					{
						$result[$diff] = array('value'=>'Not in Active Directory','class'=>'not_ad');
					}
				}
				
				//sort the final array
				ksort($result);
				
			}
			else if ($action == 'find_old')
			{
				foreach($ldap_response as $lr)
				{
					if(isset($lr['cn']))
					{
						//convert the last login to unix time
						$lastLogon = (($lr['lastlogon'][0]/10000000)-11644473600);
						
						//if user hasn't logged on in more than 60 days
						if($lastLogon < time() - (86400 * 60))
						{
							$result[trim(strtoupper($lr['cn'][0]))] = array('value'=>'Last Active Directory Logon: ' . date('F d, Y',$lastLogon));
						}
					}
				}
			}
		
			$this->set('computers',$result);
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
    
