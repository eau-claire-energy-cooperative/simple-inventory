<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;

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
					$lastLogon = intval(($lr['lastlogontimestamp'][0]/10000000)-11644473600);

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
        $newDevice->notes = '';  // should set default value in DB

        if($Computer->save($newDevice)) {
        	//create log entry
        	$this->_saveLog($this->request->getSession()->read('User.username'),
                          sprintf("Device %s added to database", $this->request->getData('ComputerName')));

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

  public function changeDecomStatus($id, $status)
	{
    $Decommissioned = $this->fetchTable('Decommissioned');

		$device = $Decommissioned->get($id);
		if($status == 'hd')
		{
      // flip from yes to no or vice versa
			$device->WipedHD = ($device->WipedHD == 'Yes' ? 'No' : 'Yes');
		}
		elseif($status == 'recycle')
		{
			$device->Recycled = ($device->Recycled == 'Yes' ? 'No' : 'Yes');
		}

		if($Decommissioned->save($device))
		{
      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf("%s status updated on %s", ucfirst($status), $device['ComputerName']));
			$this->Flash->success(sprintf('%s status updated', $device['ComputerName']));
		}
		else
    {
		  $this->Flash->error(sprintf('Error updating status for %s', $device['ComputerName']));
		}

    return $this->redirect('/inventory/decommission');
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

  public function confirmDecommission($id)
	{
    $this->set('active_menu', 'manage');

    $Computer = $this->fetchTable('Computer');
		$device = $Computer->find('all', ['contain'=>['CheckoutRequest', 'DeviceType', 'LicenseKey'],
                                      'conditions'=>['Computer.id'=>$id]])->first();

    // check for any errors that would prevent decommissioning of this device
    $errors = $this->_canDelete($device, true);

    // if confirmation is given make sure errors are corrected
		if ($this->request->is('post') && count($errors) == 0) {
      $Decommissioned = $this->fetchTable('Decommissioned');

      $decom = $Decommissioned->newEmptyEntity();
      $decom->ComputerName = $device->ComputerName;
      $decom->SerialNumber = $device->SerialNumber;
      $decom->AssetId = $device->AssetId;
      $decom->CurrentUser = $device->CurrentUser;
      $decom->Location = $device->ComputerLocation;
      $decom->Manufacturer = $device->Manufacturer;
      $decom->Model = $device->Model;
      $decom->OS = $device->OS;
      $decom->Memory = $device->Memory;
      $decom->CPU = $device->CPU;
      $decom->NumberOfMonitors = $device->NumberOfMonitors;
      $decom->IPaddress =  $device->IPaddress;
      $decom->MACaddress =  $device->MACaddress;
      $decom->LastUpdated =  $device->LastUpdated;
      $decom->WipedHD = $this->request->getData('WipedHD');
      $decom->Recycled = $this->request->getData('Recycled');
      $decom->RedeployedAs = $this->request->getData('RedeployedAs');
      $decom->notes = $this->request->getData('notes');
      $decom->device_attributes = $device['device_type']['attributes'];

      if($Decommissioned->save($decom))
      {
        // send an email to admins
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf("%s has been decommissioned", $device['ComputerName']));
        $this->_send_email(sprintf("%s has been decommissioned", $device['ComputerName']),
                           sprintf("A device has been decommissioned, the details are below. <br /><br />Device Name: %s <br />Serial Number: %s", $device['ComputerName'], $device['SerialNumber']));

        // delete the device
        $Computer->delete($device);

        //delete associated records
        $db = ConnectionManager::get('default');
        $db->delete('application_installs', ['comp_id'=>$device['id']]);
        $this->fetchTable('Service')->deleteQuery()->where(['comp_id'=>$device['id']])->execute();
        $this->fetchTable('Disk')->deleteQuery()->where(['comp_id'=>$device['id']])->execute();

        $this->Flash->success(sprintf("Device %s has been decommissioned", $device['ComputerName']));
        return $this->redirect('/inventory/computerInventory');
      }
      else
      {
        $this->Flash->error(sprintf("There was an error decommissioning %s", $device['ComputerName']));
      }
  	}

    $this->set('title', sprintf("Decommission %s", $device['ComputerName']));

    $this->set('errors', $errors);
    $this->set('device', $device);
	}

  public function dashboard(){
    $this->set('title', 'Dashboard');

    // set some totals
    $this->set('total_devices', $this->fetchTable('Computer')->find('all')->count());
    $this->set('total_types', $this->fetchTable('DeviceType')->find('all')->count());
    $this->set('total_applications', $this->fetchTable('Application')->find('all')->count());

    // determine if there are any checkout requests
    $new = $this->fetchTable('CheckoutRequest')->find('all', ['contain'=>['DeviceType'],
                                                      'conditions'=>['CheckoutRequest.status'=>'new'],
                                                      'order'=>['CheckoutRequest.check_out_date']])->all();

    $this->set('new_checkout', $new);

    // determine if there are any expired checkout requests
    $expired = $this->fetchTable('CheckoutRequest')->find('all',  ['contain'=>['DeviceType', 'Computer'],
                                              'conditions'=>['CheckoutRequest.status'=>'active', 'CheckoutRequest.check_in_date < now()'],
                                              'order'=>['CheckoutRequest.check_out_date']])->all();

    $this->set('expired_checkout', $expired);

    // pull in lifecycle list
    $lifecycles = $this->fetchTable('Lifecycle')->find('all')->all();
    $this->set('lifecycles', $lifecycles);

    # list all licenses
    $licenses = $this->fetchTable('License')->find('all', ['contain'=>'LicenseKey',
                                                           'order'=>['License.LicenseName'=>'asc']])->all();
    $this->set('licenses', $licenses);

    // pull in a list of recent activity
    $logs = $this->fetchTable('Logs')->find('all', ['conditions'=>['Logs.LOGGER'=>'Website'],
                                                    'order'=>['Logs.id'=>'desc']])->limit(100)->all();
	 	$this->set('logs', $logs);

    // to highlight devices for log parsing
    $this->set('inventory', $this->fetchTable('Computer')->find('list', ['keyField'=>'ComputerName', 'valueField'=>'id'])->toArray());

    $this->viewBuilder()->addHelper('License');
    $this->viewBuilder()->addHelper('Lifecycle');
    $this->viewBuilder()->addHelper('LogParser');
  }

  public function decommission() {
	  $this->set('active_menu', 'manage');
		$this->set('title','Decommissioned Devices');
    $this->set('decommission', $this->fetchTable('Decommissioned')->find('all', ['order'=> ['LastUpdated ASC']])->all());
  }

  public function delete($id) {
    $Computer = $this->fetchTable('Computer');

    //get the name of the computer for logging
    $computer = $Computer->find('all', ['contain'=>['CheckoutRequest', 'LicenseKey', 'DeviceType'],
                                        'conditions'=>['Computer.id'=>$id]])->first();

    $errors = $this->_canDelete($computer, false);

    if($computer != null && count($errors) == 0)
    {
      if ($Computer->delete($computer)) {

        //delete associated records
        $db = ConnectionManager::get('default');
        $db->delete('application_installs', ['comp_id'=>$id]);
        $this->fetchTable('Service')->deleteQuery()->where(['comp_id'=>$id])->execute();
        $this->fetchTable('Disk')->deleteQuery()->where(['comp_id'=>$id])->execute();

	    	$message = sprintf("%s: %s has been deleted ", $computer['device_type']['name'], $computer['ComputerName']);

	    	$this->_saveLog($this->request->getSession()->read('User.username'),
                        $message);
	      $this->Flash->success($message);
	    }

      return $this->redirect(array('action' => 'computerInventory'));
    }
    else
    {
      // display the errors
      foreach($errors as $e)
      {
        $this->Flash->error($e);
      }
      return $this->redirect('/inventory/moreInfo/' . $id);
    }
	}

  public function deleteDecom($id){
    $Decommissioned = $this->fetchTable('Decommissioned');
    $decom = $Decommissioned->get($id);

    if($Decommissioned->delete($decom))
    {
      $message = sprintf('%s has been permanently deleted', $decom['ComputerName']);
      $this->_saveLog($this->request->getSession()->read('User.username'), $message);
      $this->Flash->success($message);
    }
    else
    {
      $this->Flash->error(sprintf("Failed to delete device %s", $decom['ComputerName']));
    }

    return $this->redirect('/inventory/decommission');
  }

  function deleteDisk($disk_id, $comp_id){

    //delete the disk and redirect back to computer info page
    $Disk = $this->fetchTable('Disk');
    $oldDisk = $Disk->get($disk_id);
    $Disk->delete($oldDisk);

    $this->Flash->success('Disk deleted');
    return $this->redirect('/inventory/moreInfo/' . $comp_id);
  }

  function doDriversUpload(){
    // load the device
    $device = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$this->request->getData('id')]])->first();

    $file = $this->request->getUploadedFile('local_file');

    // make sure file is a zip file
    if(in_array($file->getClientMediaType(), ['application/x-zip', 'application/x-zip-compressed']))
    {
      // move the file
      $targetPath = sprintf("%sdrivers/%s", WWW_ROOT, $device['driver_filename']);
      $file->moveTo($targetPath);

      $this->Flash->success(sprintf('Driver File <i>%s</i> Uploaded', $device['driver_filename']), ['escape'=>false]);
    }
    else
    {
      $this->Flash->error("Driver file must be a zip file" . $file->getClientMediaType());
    }

    return $this->redirect('/inventory/more_info/' . $device['id']);
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
      $originalData = $Computer->find('all', ['contain'=>['Application', 'CheckoutRequest', 'DeviceType', 'Disk', 'LicenseKey', 'LicenseKey.License', 'Location'],
                                                             'conditions'=>['Computer.id'=>$this->request->getData('id')]])->first();

      // check if the checkout request attribute is being changed
      if($originalData['CanCheckout'] == 'true' && $this->request->getData('CanCheckout') == 'false' && count($originalData['checkout_request']) > 0)
      {
        // going from true to false check if there are any checkout requests
        $this->Flash->error(sprintf('Update Failed - this device is part of <a href="%s">one or more checkout requests</a>, please correct this before changing the Available For Checkout attribute',
                                    \Cake\Routing\Router::url('/checkout/requests')), ['escape'=>false]);
        return $this->redirect("/inventory/moreInfo/" . $this->request->getData('id'));
      }

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
      //$dirty = array_combine($originalData->getDirty(), $originalData->extract($originalData->getDirty()));
      //$this->Flash->success(json_encode($dirty));
      if($Computer->save($originalData))
      {
        $this->_saveLog($this->request->getSession()->read('User.username'), $originalData['ComputerName'] . ' has been updated');
        $this->Flash->success('Device updated');
      }
      else
      {
        $this->Flash->error('Error updating device');
      }

	    return $this->redirect("/inventory/moreInfo/" . $this->request->getData('id'));
	  }
	}

  public function import(){
    $this->set('title', 'Import Devices');

    if($this->request->is('post'))
    {
      $file = $this->request->getUploadedFile('csvFile');

      // make sure file is a csv
      if(in_array($file->getClientMediaType(), ['text/plain', 'text/csv']))
      {
        // move the file and open
        $targetPath = sprintf("%suploads/import_devices.csv", WWW_ROOT);
        $file->moveTo($targetPath);

        $csv = array_map('str_getcsv', file($targetPath));

        //attempt to get the default location id and device types list
  			$defaultLocation = $this->fetchTable('Location')->find('all', ['conditions'=>['Location.is_default'=>'true']])->first();
        $deviceTypes = $this->fetchTable('DeviceType')->find('list', ['keyField'=>function($d){
                                                                        return $d->get('slug');
                                                                       }, 'valueField'=>"id"])->toArray();
  			if($defaultLocation)
  			{
          //verify each entry's device type
          $passedCheck = true;
          $Computer = $this->fetchTable('Computer');

          foreach($csv as $row){
            //check if device with this name already exists
            if($Computer->find('all', ['conditions'=>['Computer.ComputerName'=>trim($row[1])]])->count() > 0)
            {
              $this->Flash->error(sprintf('Cannot import devices, duplicate device name %s', $row[1]));
              $passedCheck = false;
            }
            else if(!in_array(strtolower($row[0]), array_keys($deviceTypes)))
            {
              $this->Flash->error(sprintf('Cannot import devices, "%s" type does not exist', $row[0]));
              $passedCheck = false;
            }
          }

          $results = [];

          //if no errors, add each to the DB
          if($passedCheck)
          {
              foreach($csv as $row){
                $newDevice = $Computer->newEmptyEntity();
        				$newDevice->ComputerName = trim($row[1]);
                $newDevice->DeviceType = $deviceTypes[strtolower($row[0])];
        				$newDevice->ComputerLocation = $defaultLocation['id'];

        				$Computer->save($newDevice);
                $this->_saveLog($this->request->getSession()->read('User.username'),
                                sprintf("Device %s added to database", $newDevice['ComputerName']));


                array_push($results, array('id'=>$newDevice->id, 'DeviceType'=> $deviceTypes[strtolower($row[0])], 'ComputerName'=>trim($row[1])));
              }

              $this->set('results', $results);

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
        $this->Flash->error('The uploade file type is invalid. File must be a CSV.');
      }
    }
  }

  public function login(){
    $this->set('title', 'Login');
    $this->viewBuilder()->setLayout('login');

    $successfulLogin = False;
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

            $this->_saveLog($session->read('User.username'),
                            "User logged in");

						$successfulLogin = True;
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

            $this->_saveLog($session->read('User.username'),
                            "User logged in");

            $successfulLogin = True;
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

    // redirect if login was successful
    if($successfulLogin)
    {
      $redirect_location = '/';
      if($session->check('redirect_url'))
      {
        // get redirect from session, if exists
        $redirect_location = $session->consume('redirect_url');
      }

      return $this->redirect($redirect_location);
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

  public function moreInfoDecommissioned($id) {
	  $this->set('active_menu', 'manage');
	 	$this->set('title','Decommissioned Device Detail');

    $device = $this->fetchTable('Decommissioned')->find('all', ['contain'=>['Location'],
                                                                'conditions'=>['Decommissioned.id'=>$id]])->first();
    $allowedAttributes = array_merge(explode(",", $device['device_attributes']), array_keys($this->DEVICE_ATTRIBUTES['REQUIRED']));

    $tables = [];

    //build the tables
    $tables['general'] = $this->_processDisplayTable(array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL']), $allowedAttributes);
    $tables['hardware'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['HARDWARE'], $allowedAttributes);
    $tables['network'] = $this->_processDisplayTable($this->DEVICE_ATTRIBUTES['NETWORK'], $allowedAttributes);

    $this->set('validAttributes',$this->DEVICE_ATTRIBUTES['REQUIRED'] + $this->DEVICE_ATTRIBUTES['GENERAL'] + $this->DEVICE_ATTRIBUTES['HARDWARE'] + $this->DEVICE_ATTRIBUTES['NETWORK']);
    $this->set('tables',$tables);

    $this->set('decommissioned', $device);

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

  function viewHistory($id){
    $this->set('title', 'View History');

    $computer = $this->fetchTable('Computer')->find('all', ['contain'=>['DeviceType'],
                                                           'conditions'=>['Computer.id'=>$id]])->first();
    $this->set('computer', $computer);
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

  function _canDelete($device, $decom){
    // check for any errors that would prevent decommissioning or deleting of this device
    $errors = [];
    if($decom && $device['device_type']['allow_decom'] == 'false')
    {
      $errors[] = sprintf('Devices of type <b>%s</b> are not eligible to be decommissioned', $device['device_type']['name']);
    }

    if(count($device['checkout_request']) > 0)
    {
      $errors[] = sprintf("This device is part of %d active, or future, checkout request(s). You must find new compatible devices, or deny these checkout requests, before removing this device.",
                        count($device['checkout_request']));
    }

    if(count($device['license_key']) > 0)
    {
      $errors[] = sprintf('This device has %d license(s) attached to it. You must delete or move these licenses before removing this device.', count($device['license_key']));
    }

    return $errors;
  }
}
?>
