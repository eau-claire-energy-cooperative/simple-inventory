<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class AdminController extends AppController {
  public $paginate = [
      'limit' => 50
  ];

  public function initialize(): void
  {
    parent::initialize();
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

  public function addLocation() {
		$this->set('title','Add Location');

    if ($this->request->is('post')) {
        $Location = $this->fetchTable('Location');
        $location = $Location->newEntity($this->request->getData());

        if(@preg_match(sprintf('/%s/', $location['auto_regex']), '') === false)
        {
          $this->Flash->error('Regular Expression syntax is invalid');
          return $this->render();
        }

        if ($Location->save($location)) {
          $this->_saveLog($this->request->getSession()->read('User.username'),
                          sprintf('Location %s has been added', $location['location']));
          $this->Flash->success(sprintf('%s has been saved.', $location['location']));
          return $this->redirect(['action' => 'location']);
        } else {
          $this->Flash->error(sprintf('Unable to add %s', $location['location']));
        }
    }
  }

  public function deleteDriver(){
    $file = new File(sprintf('%sdrivers/%s', WWW_ROOT, $this->request->getQuery('file')));

    // attempt to delete the file
    if($file->delete())
    {
      $this->Flash->success(sprintf("%s deleted", $file->name));
    }
    else
    {
      $this->Flash->error(sprintf("There was a problem deleting %s", $file->name));
    }
    return $this->redirect("/admin/manage_drivers");
  }

  public function deleteLocation($id) {
    $Location = $this->fetchTable('Location');
    $location = $Location->get($id);

    if ($Location->delete($location)) {
      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf("Location %s has been deleted", $location['location']));
      $this->Flash->success(sprintf("%s has been deleted", $location['location']));
      return$this->redirect(['action' => 'location']);
    }
 }

  function downloads(){
		$this->set('title','Downloads');
	}

  public function editLocation($id) {
	  $this->set('title','Edit Location');

    // get this location
    $Location = $this->fetchTable('Location');
    $location = $Location->get($id);

  	if ($this->request->is('get')) {
      $this->set('location', $location);
 		}
 		else
 		{
      $Location->patchEntity($location, $this->request->getData());

      if(@preg_match(sprintf('/%s/', $location['auto_regex']), '') === false)
      {
        $this->Flash->error('Regular Expression syntax is invalid');
        return $this->render();
      }

    	if ($Location->save($location)) {
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf("Location %s has been updated", $location['location']));
        $this->Flash->success(sprintf("%s has been updated", $location['location']));
        return $this->redirect(array('action' => 'location'));
    	}
    	else
    	{
        $this->Flash->error('Unable to update your entry.');
    	}

      return $this->redirect('/admin/location');
 		}
	}

  public function editSetting($id = null){
		$this->set('title','Add Setting');

    $Setting = $this->fetchTable('Setting');

    if($this->request->is('get'))
		{
			if(isset($id))
			{
				//get the information about this id
				$this->set('title','Edit Setting');
				$this->set('setting', $Setting->get($id));
			}
      else{
        $this->set('setting', $Setting->newEmptyEntity());
      }
		}
		else
		{
      $setting = $Setting->newEntity($this->request->getData());

			if ($Setting->save($setting)) {
        $this->Flash->success('Setting saved');

        return $this->redirect(['action' => 'settings2']);
    	}
    	else
    	{
        $this->Flash->error('Unable to update the setting');
    	}
		}
	}

  public function editUser($id = null) {
		$this->set('title','Edit User');
    $User = $this->fetchTable('User');

    if ($this->request->is('get')) {

    	if($this->request->getQuery('action') != null && $this->request->getQuery('action') == 'delete'){

        $user = $User->get($id);
  			$User->delete($user);

        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf("%s has been deleted", $user['name']));
  			$this->Flash->success(sprintf("%s has been deleted", $user['name']));

        return $this->redirect(array('action'=>'users'));
  		}
		  else
		  {
        if($id != null)
        {
          $this->set('user', $User->get($id));
        }
        else
        {
          $this->set('title','New User');
          $this->set('user', $User->newEmptyEntity());
        }
		  }
 	  }
 		else
 		{
      $user = $User->newEntity($this->request->getData());

      //hash the password - if needed
 			if($this->request->getData('password_original') == null ||
 			($this->request->getData('password_original') != null && $this->request->getData('password_original') != $this->request->getData('password')))
 			{
 			  $user->password = md5($this->request->getData('password'));
		  }

      if ($User->save($user)) {

        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf("User %s has been saved", $user['name']));
      	$this->Flash->success(sprintf("Saved %s", $user['name']));
      	return $this->redirect(['action' => 'users']);
      }
      else
      {
      	$this->Flash->error(sprintf('Unable to save %s', $user['name']));
      }
 		}
	}

  function index(){
    $this->set('title', 'Admin');
  }

  public function location() {
 	  $this->set('title','Locations');

    $locations = $this->fetchTable('Location')->find('all', ['contain' => ['Computer'],
                                                             'order'=> ['is_default desc, location ASC']])->all();
    $this->set('location', $locations);  // gets all data
  }
  public function logs()	{
	 	$this->set('title','Logs');
    $this->viewBuilder()->addHelper('LogParser');

    $logs = $this->fetchTable('Logs')->find('all', ['order'=>['Logs.id'=>'desc']]);
	 	$this->set('logs',$this->paginate($logs));

		$this->set('inventory', $this->fetchTable('Computer')->find('list', ['keyField'=>'ComputerName', 'valueField'=>'id'])->toArray());
	}

  public function manageDrivers(){
    $this->set('title', 'Manage Drivers');

    $drivers = new Folder(sprintf("%sdrivers", WWW_ROOT));
    $this->set('drivers', $drivers->find(".*\.zip"));
  }

  public function settings(){
    $this->set('title', 'Settings');

    // set some attributes
    $this->set('homeAttributes', array_merge($this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));
    $this->set('infoAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    // load locations
    $locations = $this->fetchTable('Location')->find('list', ['keyField'=>"id",
                                                            "valueField"=>"location",
                                                            'order'=>'Location.is_default desc, Location.Location asc'])->toArray();
    $this->set('locations', $locations);
    $this->set('encrypted', Configure::read('Settings.encrypt'));

    if($this->request->is('post'))
		{
			//get all the settings
      $Setting = $this->fetchTable('Setting');
			$db_settings = $Setting->find('all')->all();

			foreach($db_settings as $aSetting){
				$key = $aSetting['key'];

				//check if we're updating
				if($this->request->getData($key) != null)
				{
					$value = $this->request->getData($key);

					if(is_array($this->request->getData($key)))
					{
						$value = implode(",", $this->request->getData($key));
					}

					$aSetting['value'] = $value;

					$Setting->save($aSetting);
				}
			}

			$this->Flash->success('Settings Saved');

		}

  }

  public function settings2($delete = null){
		$this->set('title', 'Advanced Settings');

    $Setting = $this->fetchTable('Setting');
		if(isset($delete))
		{
			//delete the id given
      $setting = $Setting->get($this->request->getQuery('id'));
			$Setting->delete($setting);

			$this->Flash->success('Setting deleted');

		}

		$this->set('settings_list', $Setting->find('all', ['order'=>['Setting.key']])->all());
	}

  public function setDefaultLocation($id){
    $Location = $this->fetchTable('Location');

		//reset all locations to false
		$Location->updateQuery()->set(['is_default'=>'false'])->execute();

    $newDefault = $Location->get($id);
		$newDefault->is_default = "true";
		$Location->save($newDefault);

    $this->_saveLog($this->request->getSession()->read('User.username'),
                    sprintf("%s is now the default location", $newDefault['location']));
    $this->Flash->success(sprintf("%s is the default location", $newDefault['location']));
		return $this->redirect(array('action'=>'location'));
	}

  public function testEmail($id){

    // get this user
    $aUser = $this->fetchTable('User')->get($id);

    // send a test email to this user
    $this->_send_email("Inventory Test Email", "This is a test email from the inventory system to make sure outgoing mail settings are correct.", $aUser['email']);

    $this->Flash->success(sprintf('Sending an email to %s', $aUser['email']));

    return $this->redirect('/admin/users');
  }

  public function users(){
    $this->set('title','Users');

		$users = $this->fetchTable('User')->find('all', ['order'=>['User.name']])->all();
		$this->set('users',$users);
	}
}
?>
