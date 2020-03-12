<?php
	
class InventoryController extends AppController {
    var $helpers = array('Html', 'Form', 'Session','Time','DiskSpace','AttributeDisplay','ProfileImage');
    var $components = array('Session','Ldap','FileUpload','Paginator','Flash');
	public $uses = array('Computer','Disk','Location', 'Programs', 'Logs','Service','Decommissioned','ComputerLogin','Setting','User','RestrictedProgram');
	
	public function beforeFilter(){
		//check if we are using a login method
		if(!$this->Session->check('authenticated')){
			//check if we are using a login method
			$loginMethod = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'auth_type')));
			
			if(isset($loginMethod) && trim($loginMethod['Setting']['value']) == 'none')
			{
				//we aren't authenticating, just keep moving
				$this->Session->write('authenticated','true');
				$this->Session->write('User.username', 'admin');
				$this->Session->write('User.name', 'Admin User');
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
		$this->layout = 'login';
		
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
						$this->Session->write('User.username', $aUser['User']['username']);
						$this->Session->write('User.name', $aUser['User']['name']);
						$this->redirect('/');
					}
					else
					{
						$this->Flash->error('Incorrect Password');
					}
				}
				else
				{
					$this->Flash->error('Incorrect Username');
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
						$this->Session->write('User.username', $aUser['User']['username']);
						$this->Session->write('User.name', $aUser['User']['name']);
						$this->redirect('/');
					}
					else
					{
						$this->Flash->error('Incorrect Username/Password');
					}
				}
				else
				{
					$this->Flash->error('Incorrect Username/Password');
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
	 	
		//get the info about this computer
	 	$this->Computer->id = $id;
		$this->Programs->id = $id;
        $this->set('computer', $this->Computer->read());
		$this->set('programs', $this->Programs->find('all',array('conditions' => array('comp_id' => $id), 'order' => array('program ASC'))));
		$this->set('services', $this->Service->find('all',array('conditions' => array('comp_id' => $id), 'order' => array('name ASC'))));
		$this->set('restricted_programs',$this->RestrictedProgram->find('list',array('fields'=>array('RestrictedProgram.name','RestrictedProgram.id'))));
		
		//figure out what attributes to display
		$validAttributes = array("ComputerName"=>"Computer Name","Location"=>"Location","CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID","AppUpdates"=>"Application Updates","Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors","IPAddress"=>"IP Address","MACAddress"=>"MAC Address","DriveSpace"=>"Drive Space","LastUpdated"=>"Last Updated","Status"=>"Status");
		$displaySetting = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'display_attributes')));
		$displayAttributes = explode(",",$displaySetting['Setting']['value']);
		$colCount = 0; //current number of columns 
		$tables = array();
		$currentTable = array();
		
		foreach(array_keys($validAttributes) as $aKey){
			if(in_array($aKey, $displayAttributes))
			{
				if($colCount >= 5){
					$tables[] = $currentTable;
					$currentTable = array();
					$colCount = 0;
				}
		
				$currentTable[] = $aKey;
				$colCount ++;
			}	
		} 

		//save the last table
		$tables[] = $currentTable;
		$this->set('validAttributes',$validAttributes);
		$this->set('tables',$tables);
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
            	
                $this->Flash->success('Your Entry has been saved.');
                $this->redirect(array('action' => 'computerInventory'));
            } else {
                $this->Flash->error('Unable to add your Entry.');
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
	            $this->Flash->success('Your entry has been updated.');
	            $this->redirect("/inventory/moreInfo/" . $this->data['Computer']['id']);
	        } else {
	            $this->Flash->error('Unable to update your entry.');
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
	    	$this->Disk->query('delete from disk where comp_id = ' . $id);
			
	    	$message = 'Computer ' . $computer['Computer']['ComputerName'] . ' has been deleted';
	    	
	    	$this->_saveLog($message);
	        $this->Flash->success($message);
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
        	
        	if(count($this->request->data['License']) > 0)
        	{
        	    $errors = 'This computer has ' . count($this->request->data['License']) . ' license(s) attached to it. You must delete or move these licenses before decomissioning.';
        	    $this->set('errors', $errors);
        	}
    	} 
    	else 
    	{
        	if ($this->Computer->save($this->request->data)) 
        	{
        		$message = 'Computer ' . $this->request->data['Computer']['ComputerName'] . ' has been decommissioned';
        		$this->_saveLog($message);
            	$this->Flash->success($message);
       			$this->transferDecom($currID);
        	} 
        	else 
        	{
            	$this->Flash->error('Unable to update your entry.');
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
			$this->Decommissioned->set('LastUpdated',$comp ['Computer']['LastUpdated']);
			$this->Decommissioned->set('WipedHD',$comp ['Computer']['WipedHD']);
			$this->Decommissioned->set('Recycled',$comp ['Computer']['Recycled']);
			$this->Decommissioned->set('RedeployedAs',$comp ['Computer']['RedeployedAs']);
			$this->Decommissioned->set('notes',$comp ['Computer']['notes']);
			
			$this->Computer->delete($id);
		
			//also delete programs and services 
			$this->Programs->query('delete from programs where comp_id = ' . $id);
			$this->Service->query('delete from services where comp_id = ' . $id);
			$this->Disk->query('delete from disk where comp_id = ' . $id);
		
			if( $this->Decommissioned->save())
			{
				$this->Flash->success("Machine with id: " . $id . " has been moved to the decommission table");
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
			$this->Flash->success('Wipe Hard Drive Status changed');
			$this->redirect(array('action' => 'decommission'));
		}
		else {
			{
				$this->Flash->error('Wipe Hard Drive Status failed to change');
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
			$this->Flash->success('Recycled Status changed');
			$this->redirect(array('action' => 'decommission'));
		}
		else {
			{
				$this->Flash->error('Recycled Status failed to change');
			}
		}
	}
	
	function active_directory_sync($action = 'find_old'){
		$this->set('title_for_layout','Active Directory Sync');
		
		if($action != null)
		{
			$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
			//set the baseDN and action
			$this->set('baseDN',$settings['ldap_computers_basedn']);
			$this->set('currentAction',$action);
			
			//get the ldap computer listing
			$this->Ldap->setup(array('host'=>$settings['ldap_host'],'port'=>$settings['ldap_port'],'baseDN'=>$settings['ldap_computers_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']));
			$ad_computers = array();
			$ldap_response = $this->Ldap->getComputers();
			
			
			$compare_computers = array();
			
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
				$compare_computers[$diff] = array('value'=>'Not in Inventory','class'=>'not_inventory');
			}

			foreach($inventory_diff as $diff){
				if($diff != '')
				{
					$compare_computers[$diff] = array('value'=>'Not in Active Directory','class'=>'not_ad');
				}
			}
			
			//sort the final array
			ksort($compare_computers);
				
			
			//get how many days back to go from GET params
			$old_computers = array();
			$days_old = 30;
			
			if(isset($this->params['url']['days_old']))
			{
			    $days_old = $this->params['url']['days_old'];
			}
			
			$this->set('days_old',$days_old);

			foreach($ldap_response as $lr)
			{
				if(isset($lr['cn']))
				{
					//convert the last login to unix time
					$lastLogon = (($lr['lastlogontimestamp'][0]/10000000)-11644473600);
					
					//if user hasn't logged on in more than x days
					if($lastLogon < time() - (86400 * $days_old))
					{
						$old_computers[trim(strtoupper($lr['cn'][0]))] = array('value'=>'Last Active Directory Logon: ' . date('F d, Y',$lastLogon));
					}
				}
			}
			
			//sort the final arrays
			ksort($old_computers);
			
		
			$this->set('old_computers',$old_computers);
			$this->set('compare_computers', $compare_computers);
		}
		
	}
	
	function do_drivers_upload(){
		
		if($this->FileUpload->success){
			$this->Flash->success('Drivers Uploaded');
		}
		else
		{
			$this->Flash->error('Error Uploading Drivers');
		}
	
		$this->redirect('/inventory/moreInfo/' . $this->data['File']['id']);
	}
	
	function loginHistory($id){
		$this->set('title_for_layout','Login History');
		
		$this->Paginator->settings = array('limit'=>50, 'order'=>array('ComputerLogin.LoginDate'=>'desc'),'conditions' => array('ComputerLogin.comp_id'=>$id));
		$history = $this->Paginator->paginate('ComputerLogin');
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));
		
		$this->set('id',$id);
		$this->set('computerName',$computer['Computer']['ComputerName']);
		$this->set('history',$history);
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
    
