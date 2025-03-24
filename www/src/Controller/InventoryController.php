<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\Routing\Router;

class InventoryController extends AppController {

  public $paginate = [
      'limit' => 50
  ];

  public function initialize(): void {
    parent::initialize();

    $this->loadComponent('Ldap');
  }

	function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
	}

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    // find settings before rendering
    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
    $this->set("settings", $settings);
  }

  function activeDirectorySync($action = 'find_old'){
		$this->set('title','Active Directory Sync');
		$this->set('active_menu', 'manage');

		if($action != null)
		{
			$settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();

			//set the baseDN and action
			$this->set('baseDN', $settings['ldap_computers_basedn']);
			$this->set('currentAction', $action);

			//get the ldap computer listing
			$this->Ldap->setup(['host'=>$settings['ldap_host'], 'port'=>$settings['ldap_port'],
                          'baseDN'=>$settings['ldap_computers_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']]);
			$ad_computers = [];
			$ldap_response = $this->Ldap->getComputers();


			$compare_computers = [];

			foreach($ldap_response as $lr)
			{
				if(isset($lr['cn']))
				{
					$ad_computers[] = trim(strtoupper($lr['cn'][0]));
				}
			}

			//get the computer inventory - filter out AD excluded devices
			$database_computers = $this->fetchTable('Computer')->find('list', ['contain'=>['DeviceType'],
                                                                          'valueField'=>'ComputerName',
                                                                          'conditions'=>["DeviceType.exclude_ad_sync" => 'false'],
                                                                          'recursive'=>1,
                                                                          'order'=> ['ComputerName ASC']])->toArray();

			//transform the names to uppercase
      $inventory_computers = [];
			foreach($database_computers as $computer)
			{
				$inventory_computers[] = trim(strtoupper($computer));
			}

			//find the differences between the two lists
			$ad_diff = array_diff($ad_computers,$inventory_computers);
			$inventory_diff = array_diff($inventory_computers,$ad_computers);

			//merge the lists
			foreach($ad_diff as $diff){
				$compare_computers[$diff] = ['value'=>'Not in Inventory','class'=>'not_inventory'];
			}

			foreach($inventory_diff as $diff){
				if($diff != '')
				{
					$compare_computers[$diff] = ['value'=>'Not in Active Directory','class'=>'not_ad'];
				}
			}

			//sort the final array
			ksort($compare_computers);


			//get how many days back to go from GET params
			$old_computers = array();
			$days_old = 30;

			if($this->request->getQuery('days_old') != null)
			{
			    $days_old = $this->request->getQuery('days_old');
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
						$old_computers[trim(strtoupper($lr['cn'][0]))] = ['value'=>'Last Active Directory Logon: ' . date('F d, Y',$lastLogon)];
					}
				}
			}

			//sort the final arrays
			ksort($old_computers);


			$this->set('old_computers',$old_computers);
			$this->set('compare_computers', $compare_computers);
		}

	}

  function add(){
    $this->set('title', 'Add a New Device');

    // set drop down list items
    $this->set('device_types', $this->fetchTable('DeviceType')->find('list', ['keyField'=>'id', 'valueField' => "name",
                                                                             'order'=>['name'=>'asc']])->toArray());
		$this->set('location', $this->fetchTable('Location')->find('list', ["keyField"=>'id', 'valueField' => "location",
                                                                        'order'=>['is_default'=>'desc', 'location'=>'asc']])->toArray());

    if ($this->request->is('post')) {
      $Computer = $this->fetchTable('Computer');

      $exists = $Computer->find('all', ['conditions'=>['Computer.ComputerName'=>trim($this->request->getData('ComputerName')),
                                                       'Computer.DeviceType'=>$this->request->getData('DeviceType')]])->first();
      //make sure this device is unique
      if($exists == null)
      {
        $newDevice = $Computer->newEntity($this->request->getData());
        $newDevice->ComputerName = trim($newDevice->ComputerName);

        if($Computer->save($newDevice)) {
        	//create log entry
        	$this->_saveLog("Device " . $this->request->getData('ComputerName') . " added to database");

          $this->Flash->success('Your Entry has been saved.');
          return $this->redirect(['action' => 'moreInfo', $newDevice->id]);

        } else {
          $this->Flash->error('Unable to add your Entry.');
        }
      }
      else {
        // create url for duplicate
        $deviceUrl = Router::url(['controller'=>'inventory', 'action'=>'moreInfo', $exists['id']]);
        $this->Flash->error(sprintf('Duplicate <a href="%s">device #%d</a> already exists', $deviceUrl, $exists['id']), ['escape'=> false]);
      }
    }
  }

  function addDisk(){
    $Disk = $this->fetchTable('Disk');
    $newDisk = $Disk->newEntity($this->request->getData());

    if($Disk->save($newDisk))
    {
      $this->Flash->success("Disk added");
    }
    else
    {
        $this->Flash->error("Error adding disk");
    }

    return $this->redirect('/inventory/moreInfo/' . $this->request->getData('comp_id'));
  }

  public function computerInventory(){
    $this->set('title', 'Current Inventory');

    $this->set('computer', $this->fetchTable('Computer')->find('all', ['contain'=>['DeviceType','Location'],
                                                                       'order'=> ['ComputerName ASC']])->all());// gets all data

    # get the display settings
    $displaySetting = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'home_attributes']])->first();
    $displayAttributes = explode(",", $displaySetting['value']);
    $this->set('displayAttributes', $displayAttributes);

    # set the attribute names
    $columnNames = ["CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID", "Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors", "AppUpdates"=>"Application Updates", "IPaddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACaddress"=>"MAC Address"];
    $this->set('columnNames', $columnNames);

    $this->viewBuilder()->addHelper('DynamicTable');
  }

  public function delete($id) {
    $Computer = $this->fetchTable('Computer');

    //get the name of the computer for logging
    $computer = $Computer->find('all', ['contain'=>['LicenseKey', 'DeviceType'],
                                        'conditions'=>['Computer.id'=>$id]])->first();

    if($computer != null && count($computer['license_key']) == 0)
    {
      if ($Computer->delete($computer)) {

	    	//also delete programs and services
        $this->fetchTable('Service')->deleteQuery()->where(['comp_id'=>$id])->execute();
        $this->fetchTable('Disk')->deleteQuery()->where(['comp_id'=>$id])->execute();

	    	$message = sprintf("%s: %s has been deleted ", $computer['device_type']['name'], $computer['ComputerName']);

	    	$this->_saveLog($message);
	      $this->Flash->success($message);
	    }

      return $this->redirect(array('action' => 'computerInventory'));
    }
    else
    {
      $this->Flash->error('Device has ' . count($computer['license_key']) . ' license(s) attached to it, remove these first.');
      return $this->redirect('/inventory/moreInfo/' . $id);
    }
	}

  function deleteDisk($disk_id, $comp_id){

    //delete the disk and redirect back to computer info page
    $Disk = $this->fetchTable('Disk');
    $oldDisk = $Disk->get($disk_id);
    $Disk->delete($oldDisk);

    $this->Flash->success('Disk deleted');
    return $this->redirect('/inventory/moreInfo/' . $comp_id);
  }

  public function edit($id= null) {
    $this->set('title', 'Edit Device');

    $locations = $this->fetchTable('Location')->find('list', ['keyField'=>'id',
                                                              'valueField'=>"location",
                                                              'order'=>'Location.is_default desc, Location.Location asc'])->toArray();
		$this->set('location', $locations);

	  if ($this->request->is('get')) {
      $computer = $this->fetchTable('Computer')->find('all', ['contain'=>['Application', 'DeviceType', 'Disk', 'LicenseKey', 'LicenseKey.License', 'Location'],
                                                             'conditions'=>['Computer.id'=>$id]])->first();

      //if device is found set page title based on device type
      $this->set('title', 'Edit ' . $computer['device_type']['name']);

      //set the attributes specific to this device type
      $this->set('allowedAttributes', explode(',', $computer['device_type']['attributes']));
      $this->set('generalAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL']));
      $this->set('hardwareAttributes', $this->DEVICE_ATTRIBUTES['HARDWARE']);
      $this->set('networkAttributes', $this->DEVICE_ATTRIBUTES['NETWORK']);

      $this->set('computer', $computer);

	  }
	  else
	  {
      $Computer = $this->fetchTable('Computer');
      $originalData = $Computer->find('all', ['contain'=>['Application', 'DeviceType', 'Disk', 'LicenseKey', 'LicenseKey.License', 'Location'],
                                                             'conditions'=>['Computer.id'=>$this->request->getData('id')]])->first();

      //check if the current user is part of the attributes
      if($this->request->getData('CurrentUser') != null && $this->request->getData('CurrentUser') != $originalData['CurrentUser'])
      {
        $ComputerLogin = $this->fetchTable('ComputerLogin');
        $newLogin = $ComputerLogin->newEmptyEntity();
        $newLogin->Username = $this->request->getData('CurrentUser');
        $newLogin->comp_id = $this->request->getData('id');
        $ComputerLogin->save($newLogin);
      }

      $Computer->patchEntity($originalData, $this->request->getData());
      if($Computer->save($originalData))
      {
        $this->_saveLog($originalData['ComputerName'] . ' has been updated');
        $this->Flash->success('Device updated');
      }
      else
      {
        $this->Flash->error('Error updating device');
      }

	    return $this->redirect("/inventory/moreInfo/" . $this->request->getData('id'));
	  }
	}

  public function login(){
    $this->set('title', 'Login');
    $this->viewBuilder()->setLayout('login');

    if($this->request->is('post'))
		{
			//check the type of login method
			$settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
      $session = $this->request->getSession();

			if($settings['auth_type'] == 'local')
			{
				//attempt to get a username that matches this password locally
				$aUser = $this->fetchTable('User')->find('all', ['conditions'=>['User.username'=>$this->request->getData('username')]])->first();

				if($aUser)
				{
					//check the passwords
					if(md5($this->request->getData('password')) == $aUser['password'])
					{
						//success!
						$session->write('authenticated','true');
						$session->write('User.username', $aUser['username']);
						$session->write('User.name', $aUser['name']);
						$session->write('User.gravatar', $aUser['gravatar']);
						return $this->redirect('/');
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
				$aUser = $this->fetchTable('User')->find('all', ['conditions'=>['User.username'=>$this->request->getData('username')]])->first();

				if($aUser)
				{
					//use the ldap component to authorize the user, first set it up
					$this->Ldap->setup(['host'=>$settings['ldap_host'],'port'=>$settings['ldap_port'],'baseDN'=>$settings['ldap_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']]);

					if($this->Ldap->auth($this->request->getData('username'), $this->request->getData('password')))
					{
						//success!
						$session->write('authenticated','true');
						$session->write('User.username', $aUser['username']);
						$session->write('User.name', $aUser['name']);
						$session->write('User.gravatar', $aUser['gravatar']);
						return $this->redirect('/');
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

  function loginHistory($id){
		$this->set('title','User History');

    $findLogins = $this->fetchTable('ComputerLogin')->find('all', ['conditions'=>['ComputerLogin.comp_id'=>$id],
                                                     'order'=>['ComputerLogin.LoginDate'=>'desc']]);


		$computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

		$this->set('id',$id);
		$this->set('computerName',$computer['ComputerName']);
		$this->set('history', $this->paginate($findLogins));
	}

  public function logout(){
    $this->request->getSession()->destroy();
    return $this->redirect('/');
  }

  public function moreInfo( $id) {
		//get the info about this computer - recurse to level 2
    $computer = $this->fetchTable('Computer')->find('all', ['contain'=>['Application', 'DeviceType', 'Disk', 'LicenseKey', 'LicenseKey.License', 'Location'],
                                                            'conditions'=>['Computer.id'=>$id], 'recursive'=>2])->first();

    // set the page title based on the device type
    $this->set('title', $computer['device_type']['name'] . ' Detail');

    //set variables for the view
    $this->set('computer', $computer);
    $this->set('lifecycles', $this->fetchTable('Lifecycle')->find('list', ['keyField'=>'application_id', 'valueField'=>'id'])->toArray());
		$this->set('services', $this->fetchTable('Service')->find('all', ['conditions' =>['comp_id' => $id],
                                                                      'order' =>['name ASC']])->all());

		//figure out what attributes to display
    $allowedAttributes = array_merge(explode(",",$computer['device_type']['attributes']), array_keys($this->DEVICE_ATTRIBUTES['REQUIRED']));

		$tables = [];

    //build the tables
		$tables['general'] = $this->_processDisplayTable(array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL']), $allowedAttributes);
    $tables['hardware'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['HARDWARE'], $allowedAttributes);
    $tables['network'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['NETWORK'], $allowedAttributes);


		$this->set('validAttributes',$this->DEVICE_ATTRIBUTES['REQUIRED'] + $this->DEVICE_ATTRIBUTES['GENERAL'] + $this->DEVICE_ATTRIBUTES['HARDWARE'] + $this->DEVICE_ATTRIBUTES['NETWORK']);
		$this->set('displayStatus', $computer['device_type']['check_running'] == 'true');
		$this->set('tables',$tables);

    // load helpers
    $this->viewBuilder()->addHelper('AttributeDisplay');
    $this->viewBuilder()->addHelper('Markdown');
  }

  function setProfileImage(){
    $session = $this->request->getSession();

    //get the user
    $User = $this->fetchTable('User');
    $aUser = $User->find('all', ['conditions'=>['User.username'=>$session->read('User.username')]])->first();

    if($aUser)
    {

        $aUser->gravatar = $this->request->getData('username');
        $session->write('User.gravatar', $this->request->getData('username'));
        $User->save($aUser);

        $this->Flash->success('Profile image set');
    }
    else
    {
        $this->Flash->error('Problem setting profile image');
    }

    return $this->redirect('/');
	}

  function _processDisplayTable($validAttributes, $selectedAttributes){
    $result = [];

    $currentRow = []; //one row in a table
    $colCount = 0; //current number of columns
    $maxCol = 5; //maximum number of table columns

    foreach(array_keys($validAttributes) as $aKey){
      if(in_array($aKey, $selectedAttributes))
      {
        if($colCount >= 5){
          $result[] = $currentRow;
          $currentRow = [];
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

  function _saveLog($message){
    $Log = $this->fetchTable('Logs');

    $aLog = $Log->newEmptyEntity();
    $aLog->LOGGER = 'Website';
    $aLog->LEVEL = 'INFO';
    $aLog->MESSAGE = $message;
    $aLog->DATED = date("Y-m-d H:i:s",time());

    $Log->save($aLog);
	}
}
?>
