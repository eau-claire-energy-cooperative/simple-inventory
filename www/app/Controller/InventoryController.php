<?php

class InventoryController extends AppController {
    var $helpers = array('Html', 'Form', 'Markdown', 'Session','Time','DiskSpace','AttributeDisplay','Menu');
    var $components = array('Session','Ldap','FileUpload','Paginator','Flash');
	  public $uses = array('Applications','Computer', 'DeviceType', 'Disk', 'Lifecycle', 'Location', 'Logs','Service','Decommissioned','ComputerLogin','Setting','User');

	public function beforeFilter(){
		$this->_check_authenticated();
	}

	public function beforeRender(){
	    parent::beforeRender();
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
						$this->Session->write('User.gravatar', $aUser['User']['gravatar']);
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
						$this->Session->write('User.gravatar', $aUser['User']['gravatar']);
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
			else
			{
			    $this->Flash->error('Login Failed. An incorrect authentication type is set in the settings. ');
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
  	$this->set('title_for_layout','Current Inventory');
      $this->set('computer', $this->Computer->find('all', array('order'=> array('ComputerName ASC'))));// gets all data

      # get the display settings
      $displaySetting = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'home_attributes')));
      $displayAttributes = explode(",",$displaySetting['Setting']['value']);
      $this->set('displayAttributes', $displayAttributes);

      # set the attribute names
      $columnNames = array("CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID", "Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors", "AppUpdates"=>"Application Updates", "IPAddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACAddress"=>"MAC Address");
      $this->set('columnNames', $columnNames);

  }

	public function moreInfo( $id) {
		//get the info about this computer
	 	$this->Computer->id = $id;
    $computer = $this->Computer->read();

    // set the page title based on the device type
    $this->set('title_for_layout',$computer['DeviceType']['name'] . ' Detail');

    //set variables for the view
    $this->set('computer', $computer);
    $this->set('lifecycles', $this->Lifecycle->find('list', array('fields'=>array('Lifecycle.application_id', 'Lifecycle.id'))));
		$this->set('services', $this->Service->find('all',array('conditions' => array('comp_id' => $id), 'order' => array('name ASC'))));

		//figure out what attributes to display
    $allowedAttributes = array_merge(explode(",",$computer['DeviceType']['attributes']), array_keys($this->DEVICE_ATTRIBUTES['REQUIRED']));

		$tables = array();

    //build the tables
		$tables['general'] = $this->_processDisplayTable(array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL']), $allowedAttributes);
    $tables['hardware'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['HARDWARE'], $allowedAttributes);
    $tables['network'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['NETWORK'], $allowedAttributes);


		$this->set('validAttributes',$this->DEVICE_ATTRIBUTES['REQUIRED'] + $this->DEVICE_ATTRIBUTES['GENERAL'] + $this->DEVICE_ATTRIBUTES['HARDWARE'] + $this->DEVICE_ATTRIBUTES['NETWORK']);
		$this->set('displayStatus', $computer['DeviceType']['check_running'] == 'true');
		$this->set('tables',$tables);
  }

	public function moreInfoDecommissioned( $id) {
	  $this->set('active_menu', 'manage');
	 	$this->set('title_for_layout','Decommissioned Device Detail');
	 	$this->Decommissioned->id = $id;

    $this->set('decommissioned', $this->Decommissioned->read());
  }

  public function import(){
    $this->set('title_for_layout', 'Import Devices');

    if($this->request->is('post')){
      $this->FileUpload->uploadDir = 'files';
      $this->FileUpload->allowedTypes = array('text/plain', 'application/vnd.ms-excel');
      $this->FileUpload->ext = 'csv';

      if($this->FileUpload->upload()){
        //load the CSV file
        $csv = array_map('str_getcsv', file(WWW_ROOT . 'files/import_devices.csv'));

        //attempt to get the default location id and device types list
  			$defaultLocation = $this->Location->find('first',array('conditions'=>array('Location.is_default'=>'true')));
        $deviceTypes = $this->DeviceType->find('list', array('fields' => array("DeviceType.slug", "DeviceType.id")));

  			if($defaultLocation)
  			{
          //verify each entries device type
          $passedCheck = true;
          foreach($csv as $row){
            //check if device with this name already exists
            if($this->Computer->find('first', array('conditions'=>array('ComputerName'=>trim($row[1])))))
            {
              $this->Flash->error('Cannot import devices, duplicate device name ' . $row[1]);
              $passedCheck = false;
            }
            else if(!in_array(strtolower($row[0]), array_keys($deviceTypes)))
            {
              $this->Flash->error('Cannot import devices, "' . $row[0] . '" type does not exist');
              $passedCheck = false;
            }
          }
          $results = array();

          //if no errors, add each to the DB
          if($passedCheck)
          {
              foreach($csv as $row){
                $this->Computer->create();
        				$this->Computer->set('ComputerName',trim($row[1]));
                $this->Computer->set('DeviceType',$deviceTypes[strtolower($row[0])]);
        				$this->Computer->set('ComputerLocation',$defaultLocation['Location']['id']);

        				$this->Computer->save();

                array_push($results, array('id'=>$this->Computer->id, 'DeviceType'=> $deviceTypes[strtolower($row[0])], 'ComputerName'=>trim($row[1])));
              }

              $this->set('results', $results);

              //upload each type of device
              $this->Flash->success(count($csv) . ' devices created');
          }
        }
        else
        {
          $this->Flash->error('Cannot import devices, no default location set');
        }
  		}
  		else
  		{
  			$this->Flash->error('Error Uploading CSV');
  		}
    }
  }

	public function add() {
		$this->set('title_for_layout','Add a New Device');

    // set drop down list items
    $this->set('device_types', $this->DeviceType->find('list', array('fields' => array("DeviceType.name"), 'order'=>array('name asc'))));
		$this->set('location', $this->Location->find('list', array('fields' => array("Location.Location"), 'order'=>array('is_default desc, location asc'))));

    if ($this->request->is('post')) {
			//trim computername
			$this->request->data['Computer']['ComputerName'] = trim($this->data['Computer']['ComputerName']);

      if(!$this->Computer->find('first', array('conditions'=>array('ComputerName'=>$this->request->data['Computer']['ComputerName']))))
      {
        if ($this->Computer->save($this->request->data)) {
        	//create log entry
        	$this->_saveLog("Device " . $this->request->data['Computer']['ComputerName'] . " added to database");

          $this->Flash->success('Your Entry has been saved.');
          $this->redirect(array('action' => 'moreInfo', $this->Computer->id));
        } else {
            $this->Flash->error('Unable to add your Entry.');
        }
      }
      else {
        $this->Flash->error('Duplicate Device Name already exists');
      }
    }
  }


	public function edit($id= null) {
		$this->set('title_for_layout','Edit Device');
		$this->set('location', $this->Location->find('list', array('fields' => array("Location.Location"),'order'=>'Location.is_default desc, Location.Location asc')));
	  $this->Computer->id = $id;

	    if ($this->request->is('get')) {
          $computer = $this->Computer->read();

          //if device is found set page title based on device type
          $this->set('title_for_layout','Edit ' . $computer['DeviceType']['name']);

          //set the attributes specific to this device type
          $this->set('allowedAttributes', explode(',', $computer['DeviceType']['attributes']));
          $this->set('generalAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL']));
          $this->set('hardwareAttributes', $this->DEVICE_ATTRIBUTES['HARDWARE']);
          $this->set('networkAttributes', $this->DEVICE_ATTRIBUTES['NETWORK']);

          //set the data to make the fields editable
	        $this->request->data = $computer;
	    }
	    else
	    {
          //pull in the original device information
          $originalData = $this->Computer->find('first', array('conditions'=>array("Computer.id" => $this->data['Computer']['id'])));

	        if ($this->Computer->save($this->request->data)) {
	            $this->Flash->success('Your entry has been updated.');
              $this->_saveLog($this->data['Computer']['ComputerName'] . " has been updated");

              //check if the current user is part of the attributes
              if(array_key_exists('CurrentUser', $this->data['Computer']) && $this->data['Computer']['CurrentUser'] != $originalData['Computer']['CurrentUser'])
              {
                $this->ComputerLogin->create();
          			$this->ComputerLogin->set('Username',$this->data['Computer']['CurrentUser']);
          			$this->ComputerLogin->set('comp_id',$this->data['Computer']['id']);
          			$this->ComputerLogin->save();
              }

	            $this->redirect("/inventory/moreInfo/" . $this->data['Computer']['id']);
	        } else {
	            $this->Flash->error('Unable to update your entry.');
	        	}
	   	}
	}

	public function delete($id) {

	    //get the name of the computer for logging
	    $this->Computer->id = $id;
	    $computer = $this->Computer->read();

	    if ($this->Computer->delete($id)) {
	    	//also delete programs and services
	    	$this->Applications->query('delete from application_installs where comp_id = ' . $id);
	    	$this->Service->query('delete from services where comp_id = ' . $id);
	    	$this->Disk->query('delete from disk where comp_id = ' . $id);

	    	$message = $computer['DeviceType']['name'] . ' ' . $computer['Computer']['ComputerName'] . ' has been deleted';

	    	$this->_saveLog($message);
	      $this->Flash->success($message);

	    }

		$this->redirect(array('action' => 'computerInventory'));
	}

  public function deleteDecom($id){

      //get the name of the device for logging
      $this->Decommissioned->id = $id;
      $computer = $this->Decommissioned->read();

      if($this->Decommissioned->delete($id))
      {
        $message = $computer['Decommissioned']['ComputerName'] . ' has been permanently deleted';
        $this->_saveLog($message);
        $this->Flash->success($message);
      }
      else
      {
        $this->Flash->error("Failed to delete device with id: " . $id);
      }

      $this->redirect(array('action'=>'decommission'));
  }

 	public function decommission() {
 	    $this->set('active_menu', 'manage');
  		$this->set('title_for_layout','Decommissioned Devices');
      $this->set('decommission', $this->Decommissioned->find('all', array('order'=> array('LastUpdated ASC'))));
  }


	public function confirmDecommission( $id = null)
	{
    $this->set('active_menu', 'manage');
		$currID = $id; //variable to pass to transferDecom
		$this->Computer->id = $id;

		$this->set('computer_id', $id);
    $this->request->data = $this->Computer->read();

		if ($this->request->is('get')) {

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
      		$message = $this->request->data['Computer']['ComputerName'] . ' has been decommissioned';
      		$this->_saveLog($message);
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

    //TODO - make this based on device type attributes
		$this->Decommissioned->create();
		$this->Decommissioned->set('ComputerName',$comp ['Computer']['ComputerName']);
		$this->Decommissioned->set('SerialNumber',$comp ['Computer']['SerialNumber']);
		$this->Decommissioned->set('AssetId',$comp ['Computer']['AssetId']);
		$this->Decommissioned->set('CurrentUser',$comp ['Computer']['CurrentUser']);
		$this->Decommissioned->set('Location',$comp ['Computer']['ComputerLocation']);
    $this->Decommissioned->set('Manufacturer',$comp ['Computer']['Manufacturer']);
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

    //send email
    $this->_send_email($comp['Computer']['ComputerName'] . ' has been decommissioned',
                      "A device has been decommissioned, the details are below. <br /><br />: Computer Name:" . $comp['Computer']['ComputerName'] . '<br /> Serial Number: ' . $comp['Computer']['SerialNumber']);

		$this->Computer->delete($id);

		//also delete programs and services
		$this->Applications->query('delete from application_installs where comp_id = ' . $id);
		$this->Service->query('delete from services where comp_id = ' . $id);
		$this->Disk->query('delete from disk where comp_id = ' . $id);

		if( $this->Decommissioned->save())
		{
			$this->Flash->success("Device with id: " . $id . " has been decommissioned");
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
		$this->set('active_menu', 'manage');

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

			//get the computer inventory - filter out AD excluded devices
			$inventory_computers = $this->Computer->find('list', array('conditions'=>array("DeviceType.exclude_ad_sync" => 'false'), 'recursive'=>1,
                             'fields'=>array('Computer.ComputerName'),'order'=> array('ComputerName ASC')));

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

		if($this->FileUpload->upload()){
			$this->Flash->success('Drivers Uploaded');
		}
		else
		{
			$this->Flash->error('Error Uploading Drivers');
		}

		$this->redirect('/inventory/moreInfo/' . $this->data['File']['id']);
	}

	function set_profile_image(){

	    //get the user
	    $aUser = $this->User->find('first',array('conditions'=>array('User.username'=>$this->Session->read('User.username'))));

	    if($aUser)
	    {

	        $aUser['User']['gravatar'] = $this->data['Gravatar']['username'];
	        $this->Session->write('User.gravatar', $this->data['Gravatar']['username']);
	        $this->User->save($aUser);

	        $this->Flash->success('Profile image set');
	    }
	    else
	    {
	        $this->Flash->error('Problem setting profile image');
	    }

	    $this->redirect('/inventory/');
	}

	function loginHistory($id){
		$this->set('title_for_layout','User History');

		$this->Paginator->settings = array('limit'=>50, 'order'=>array('ComputerLogin.LoginDate'=>'desc'),'conditions' => array('ComputerLogin.comp_id'=>$id));
		$history = $this->Paginator->paginate('ComputerLogin');
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$id)));

		$this->set('id',$id);
		$this->set('computerName',$computer['Computer']['ComputerName']);
		$this->set('history',$history);
	}

  function add_disk(){

    if($this->Disk->save($this->request->data))
    {
      $this->Flash->success("Disk added");
    }
    else
    {
        $this->Flash->error("Error adding disk");
    }

    $this->redirect('/inventory/moreInfo/' . $this->request->data['Disk']['comp_id']);
  }

  function delete_disk($disk_id, $comp_id){

    //delete the disk and redirect back to computer info page
    $this->Disk->delete($disk_id);

    $this->Flash->success('Disk deleted');
    $this->redirect('/inventory/moreInfo/' . $comp_id);
  }

	function _saveLog($message){
		$this->Logs->create();
		$this->Logs->set('LOGGER','Website');
		$this->Logs->set('LEVEL','INFO');
		$this->Logs->set('MESSAGE',$message);
		$this->Logs->set("DATED",date("Y-m-d H:i:s",time()));
		$this->Logs->save();
	}

	function _processDisplayTable($validAttributes, $selectedAttributes){
        $result = array();

        $currentRow = array(); //one row in a table
        $colCount = 0; //current number of columns
        $maxCol = 5; //maximum number of table columns

	    foreach(array_keys($validAttributes) as $aKey){
	        if(in_array($aKey, $selectedAttributes))
	        {
	            if($colCount >= 5){
	                $result[] = $currentRow;
	                $currentRow = array();
	                $colCount = 0;
	            }

	            $currentRow[] = $aKey;
	            $colCount ++;
	        }
	    }

	    if(count($currentRow) > 0)
	    {
	       $result[] = $currentRow;
	    }

	    return $result;
	}
}
