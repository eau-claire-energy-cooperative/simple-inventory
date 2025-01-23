<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class InventoryController extends AppController {

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

  public function computerInventory(){
    $this->set('title', 'Current Inventory');

    $this->set('computer', $this->fetchTable('Computer')->find('all', ['contain'=>['DeviceType','Location'],
                                                                       'order'=> ['ComputerName ASC']])->all());// gets all data

    # get the display settings
    $displaySetting = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'home_attributes']])->first();
    $displayAttributes = explode(",", $displaySetting['value']);
    $this->set('displayAttributes', $displayAttributes);

    # set the attribute names
    $columnNames = ["CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID", "Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors", "AppUpdates"=>"Application Updates", "IPAddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACAddress"=>"MAC Address"];
    $this->set('columnNames', $columnNames);
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
}
?>
