<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\View\JsonView;
use Cake\Datasource\ConnectionManager;
use \Cake\ORM\Query;

class ApiController extends AppController {
  var $RESULT_CODES = ['success', 'warning', 'error'];
  var $json_data = null;


  public function viewClasses(): array
  {
      return [JsonView::class];
  }

  public function initialize(): void
  {
    parent::initialize();

    //$this->viewBuilder()->setLayout('ajax');
    $this->viewBuilder()->setClassName("Json");

    $this->loadComponent('Ldap');
  }

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    //check the auth value
    $auth_key = $this->request->getHeaderLine('X-Auth-Key');

    $auth_value = $this->fetchTable('Setting')->find('all',['conditions'=>['Setting.key'=>'api_auth_key']])->first();

    if($auth_key != $auth_value['value'])
    {
      //show error message, stop all processing here
      $this->set('result', ['type'=>$this->RESULT_CODES[2], 'message'=>'auth key value is invalid']);
      $this->viewBuilder()->setOption('serialize', 'result');
      $event->setResult($this->render());
    }
  }

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    $this->response = $this->response->withType('json');
  }

  function addLog(){
    $this->request->allowMethod(['post']);
		$result = [];

		$this->_log($this->request->getData('date'), $this->request->getData('logger'),
                $this->request->getData('level'), $this->request->getData('message'));

		$result['type'] = 'success';

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
	}

  public function applications(){
    $this->request->allowMethod(['get', 'delete', 'post']);
    $result = [];

    if($this->request->is('get'))
    {
      $computerId = $this->request->getQuery('id');

			//pull in computer and apps
      $computer = $this->fetchTable('Computer')->find('all', ['contain'=>['Application'],
                                                              'conditions'=>['Computer.id'=>$computerId]])->first();

			if(count($computer['application']) > 0)
			{
				$result['type'] = "success";
				$result['result'] = $computer['application'];
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = sprintf('cannot find applications for computer id %d', $computerId);
			}
    }
    else if($this->request->is('delete'))
    {
      $connection = ConnectionManager::get('default');

      // remove from join table directly
      $connection->execute("delete from application_installs where comp_id = ?",
                           [$this->request->getData('id')],
                           ['integer']);

			$result['type'] = 'success';
			$result['result'] = sprintf("Applications deleted for computer id %d", $this->request->getData('id'));
    }
    else if($this->request->is('post'))
    {
      $result['type'] = 'success';
      $result['result'] = [];

      // go through an array of applications and attempt to add each one
      $Application = $this->fetchTable('Application');
      foreach($this->request->getData('applications') as $newApp){
        //lookup this application
        $application = $Application->find('all', ['conditions'=>['Application.name'=>$newApp['application'],
                                                                 'Application.version'=>$newApp['version']]])->first();

        if($application == null)
        {
          //add this application to the database
          $application = $Application->newEmptyEntity();
          $application->name = $newApp['application'];
          $application->version = $newApp['version'];
          $Application->save($application);

        }

        $compCheck = $this->fetchTable('Computer')->find('all', ['contain'=>['Application'=> function(Query $q) use ($application){
                                                              return $q->where(['Application.id'=>$application['id']]);
                                                            }],
                                                'conditions'=>['Computer.id'=>$this->request->getData("id")]])->first();


        if(count($compCheck['application']) == 0)
        {
          $Application->Computer->link($application, [$compCheck]);
  			  $result['result'][] = ['type'=>'success',
                                 'message'=>sprintf('Application %s added for computer id %d', $application['name'], $this->request->getData("id"))];
        }
        else
        {
          $result['result'][] = ['type'=>'error',
                                 'message'=>sprintf('Application %s already assigned to computer id %d', $application['name'], $this->request->getData("id"))];
        }
      }
    }

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  function deviceType(){
    $this->request->allowMethod(['get']);

    $result = [];

    $types = $this->fetchTable('DeviceType')->find('all', ['order'=>['DeviceType.name asc']])->all();

    if($types)
    {
      $result['type'] = $this->RESULT_CODES[0];
      $result['result'] = $types;
    }
    else
    {
      $result["type"] = $this->RESULT_CODES[3];
      $result['message'] = 'error getting device types';
    }

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  public function disk(){
    $this->request->allowMethod(['get','post','delete']);

    $Disk = $this->fetchTable('Disk');
    if($this->request->is('get'))
		{
			$disks = $Disk->find('all',['conditions'=>['Disk.comp_id'=>$this->request->getQuery('comp_id')],
                                                     'order'=>['Disk.label']]);

			if($disks->count() > 0)
			{
				$result['type'] = "success";
				$result['result'] = $disks->all();
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'error getting disks';
			}
		}
		else if($this->request->is('post'))
		{

			$aDisk = $Disk->find('all', ['conditions'=>['Disk.label'=>$this->request->getData('label'),
                                                  'Disk.comp_id'=>$this->request->getData('comp_id')]])->first();

			if($aDisk != null)
			{
				//update the disk
				$aDisk->total_space = $this->request->getData('total_space');
				$aDisk->space_free = $this->request->getData('space_free');
				$aDisk->type = $this->request->getData('type');

				$Disk->save($aDisk);

				$result['type'] = "success";
				$result['message'] = sprintf('Disk updated for computer %d', $aDisk->comp_id);
			}
			else
			{
				//create a new disk entry
				$aDisk = $Disk->newEmptyEntity();
        $aDisk->comp_id = $this->request->getData('comp_id');
        $aDisk->label = $this->request->getData('label');
        $aDisk->total_space = $this->request->getData('total_space');
				$aDisk->space_free = $this->request->getData('space_free');
				$aDisk->type = $this->request->getData('type');
				$Disk->save($aDisk);

				$result["type"] = 'success';
				$result['message'] = sprintf('Disk added for computer %d', $aDisk->comp_id);
			}
		}
		else if($this->request->is('delete'))
		{

      $aDisk = $Disk->get($this->request->getData('id'));

			//delete
			$Disk->delete($aDisk);

			$result["type"] = 'success';
			$result['message'] = sprintf('Disk %s deleted for computer %d', $aDisk['label'], $aDisk['comp_id']);
		}

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  public function inventory(){
    $this->request->allowMethod(['get', 'post', 'put']);

    if($this->request->is('get'))
    {
      $computerName = trim($this->request->getQuery('computer'));

			//check if this computer exists
			$computer = $this->fetchTable('Computer')->find('all', ['contain'=>['DeviceType'],
                                                              'conditions'=>['ComputerName'=>$computerName]])->first();

			if($computer != null){
				$result['type'] = 'success';

        //create in the attributes list from the device type
        $allowedAttributes = array_merge(explode(",",$computer['device_type']['attributes']), array_keys($this->DEVICE_ATTRIBUTES['REQUIRED']), ["id", "DeviceType", "notes"]);

        //set device type as a string
        $computer['DeviceType'] = $computer['device_type']['slug'];
        $computer->unset('device_type');

        // get the difference from what is in the DB vs what we want to see
        $extraAttributes = array_diff(array_keys($computer->toArray()),
                                      $allowedAttributes);

        // remove unncessary attributes
        foreach($extraAttributes as $e){
          $computer->unset($e);
        }

				$result['result'] = $computer;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = sprintf('computer %s does not exist', $computerName);
			}
    }
    else if($this->request->is('put'))
    {
      $Computer = $this->fetchTable('Computer');

      //get this computer first from the DB
			$aComputer = $Computer->find('all', ['contain'=>['DeviceType'],
                                           'conditions'=>['Computer.id'=>$this->request->getData('id')]])->first();

      //create in the attributes list from the device type
      $allowedAttributes = explode(",", $aComputer['device_type']['attributes']);

      $Computer->patchEntity($aComputer, $this->request->getData());
      $this->_saveDeviceHistory($aComputer, 'Updater');
      //this could fail validation
			if($Computer->save($aComputer))
      {
        //also add the computer login information, if that attribute exists
        if(in_array("CurrentUser", $allowedAttributes))
        {
          $ComputerLogin = $this->fetchTable('ComputerLogin');
          $login = $ComputerLogin->newEmptyEntity();

          $login->Username = $this->request->getData('CurrentUser');
          $login->comp_id = $this->request->getData('id');
          $login->LoginDate = $aComputer['LastUpdated'];

          $ComputerLogin->save($login);
        }

  			$result['type'] = 'success';
  			$result['message'] = sprintf('computer %s has been updated', $aComputer['ComputerName']);
      }
      else
      {
        $result['type'] = 'error';
  			$result['message'] = sprintf('computer %s could not be updated', $aComputer['ComputerName']);
      }
    }
    else if($this->request->is('post'))
    {
      $Computer = $this->fetchTable('Computer');
      $aComputer = $Computer->find('all', ['conditions'=>['ComputerName'=>$this->request->getData('ComputerName')]])->first();
      $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();

      if($aComputer == null)
      {
        // try 3 ways to set location, LDAP, REGEX, or use the default
        $location = null;
        if($settings['ldap_auto_location'] == 'true')
        {
          $this->Ldap->setup(['host'=>$settings['ldap_host'], 'port'=>$settings['ldap_port'],
                              'baseDN'=>$settings['ldap_computers_basedn'],'user'=>$settings['ldap_user'],'password'=>$settings['ldap_password']]);

          // see if we can find this computer and get the location
          $location_name = $this->Ldap->getComputerLocation(trim($this->request->getData('ComputerName')));

          if($location_name != null)
          {
            // check if this location matches one in the database
            $location = $this->fetchTable('Location')->find('all', ['conditions'=>['Location.location'=>$location_name]])->first();
            $this->_log(date("Y-m-d H:i:s",time()), "Updater", "INFO", sprintf("%s location found via LDAP", $this->request->getData('ComputerName')));
          }
        }

        if($location == null)
        {
          // attempt to auto find the location based on location grouping
          $location = $this->fetchTable('Location')->find('all')->where(["auto_regex != ''", sprintf("'%s' REGEXP auto_regex", $this->request->getData('ComputerName'))])->first();
          $this->_log(date("Y-m-d H:i:s",time()), "Updater", "INFO", sprintf("%s location found via REGEX", $this->request->getData('ComputerName')));
        }

        if($location == null)
        {
          //attempt to get the default location id
          $location = $this->fetchTable('Location')->find('all', ['conditions'=>['Location.is_default'=>'true']])->first();
        }

  			// load the device list device types list
        $deviceTypes = $this->fetchTable('DeviceType')->find('list', ['keyField'=>function($d){
                                                                        return $d->get('slug');
                                                                       }, 'valueField'=>"id"])->toArray();
  			if($location != null)
  			{
          //check that device type exists
          if(array_key_exists(strtolower($this->request->getData('DeviceType')), $deviceTypes))
          {
    				$aComputer = $Computer->newEmptyEntity();
    				$aComputer->ComputerName = trim($this->request->getData('ComputerName'));
            $aComputer->DeviceType = $deviceTypes[strtolower($this->request->getData('DeviceType'))];  // convert slug to id
    				$aComputer->ComputerLocation = $location['id'];
            $aComputer->notes = '';

            // save
    				$Computer->save($aComputer);

    				$result['type'] = 'success';
    				$result['message'] = sprintf('computer %s added to database', $aComputer['ComputerName']);
    				$result['result'] = ['id'=>$aComputer->id, 'location'=>$location['location']];
          }
          else
          {
            $result['type'] = 'error';
    				$result['message'] = sprintf('error finding device type ', $this->request->getData('DeviceType'));
          }
  			}
  			else
  			{
  				$result['type'] = 'error';
  				$result['message'] = 'error finding default location';
  			}
      }
      else {
        $result['type'] = 'error';
        $result['message'] = 'cannot add duplicate device name';
      }
    }

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  public function location(){
    $this->request->allowMethod(['get']);
    $result = [];

		if($this->request->getQuery('default') == null)
		{
			$locations = $this->fetchTable('Location')->find('all', ['order'=>['Location.location']])->all();

			if($locations)
			{
				$result['type'] = "success";
				$result['result'] = $locations;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'error getting locations';
			}
		}
		else
		{
			$default_location = $this->fetchTable('Location')->find('all', ['conditions'=>['Location.is_default'=>'true'],
                                                                      'order'=>['Location.location']])->first();

			if($default_location != null)
			{
				$result['type'] = "success";
				$result['result'] = $default_location;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'error getting default location';
			}
		}

    $this->set('result',$result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  function sendEmail(){
    $this->request->allowMethod(['post']);
		$result = [];

		$subject = $this->request->getData('subject');
		$message = $this->request->getData('message');

		if(isset($subject) && isset($message))
		{
      $this->_send_email($subject, $message);

			$result['type'] = $this->RESULT_CODES[0];
			$result['message'] = sprintf('sending email %s', $subject);
		}
		else
		{
			$result["type"] = $this->RESULT_CODES[2];
			$result['message'] = 'need a subject and message content to send';
		}

		$this->set('result', $result);
    $this->viewBuilder()->setOption('serialize', 'result');
	}

  public function services(){
    $this->request->allowMethod(['get', 'post', 'delete', 'put']);
		$result = [];

    if($this->request->is('get'))
		{
			$computerId = $this->request->getQuery('id');

			$services = $this->fetchTable('Service')->find('all', ['conditions' => ['comp_id' => $computerId],
                                               'order' => ['name ASC']]);

			if($services->count() > 0)
			{
				$result['type'] = "success";
				$result['result'] = $services->all();
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = sprintf('cannot find services for computer id %d', $computerId);
			}
		}
    else if($this->request->is('delete'))
		{
			$computerId = $this->request->getData('id');

			$this->fetchTable('Service')->deleteQuery()->where(['comp_id'=>$computerId])->execute();

      $result['type'] = 'success';
			$result['result'] = sprintf("Services deleted for computer id %d", $computerId);
		}
    else if($this->request->is('post'))
    {
      $result['type'] = 'success';
      $result['result'] = [];

      //go through an array of services and attempt to add each one
      $Service = $this->fetchTable('Service');

      foreach($this->request->getData('services') as $newService)
      {
        $service = $Service->newEmptyEntity();

        $service->name = $newService['name'];
        $service->startmode = $newService['mode'];
        $service->status = $newService['status'];
        $service->comp_id = $this->request->getData('id');

			  $Service->save($service);

			  $result['result'][] = ['type'=>'success','result'=>sprintf("Service %s added for computer id %d", $service['name'], $service['comp_id'])];
      }
    }
    else if($this->request->is('put'))
    {
      $Service = $this->fetchTable('Service');
      $existingService = $Service->find('all', ['conditions'=>['Service.name'=>$this->request->getData('name'),
                                                               'Service.comp_id'=>$this->request->getData('id')]])->first();

      if($existingService != null)
      {
        $existingService->startmode = $this->request->getData('mode');
        $existingService->status = $this->request->getData('status');

        $Service->save($existingService);

        $result['type'] = 'success';
  			$result['result'] = sprintf("Service %s updated for computer id %d", $existingService['name'], $existingService['comp_id']);
      }
    }

    $this->set('result', $result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  public function settings(){
    $this->request->allowMethod(['get']);
		$result = [];

    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value',
                                                            'order'=>['Setting.key']])->toArray();

		if($settings)
		{
			$result['type'] = "success";
			$result['result'] = $settings;
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'error getting settings';
		}

    $this->set('result', $result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  protected function _log($date, $logger, $level, $message){
    $Log = $this->fetchTable('Logs');

    $aLog = $Log->newEmptyEntity();
    $aLog->LOGGER = $logger;
    $aLog->LEVEL = $level;
    $aLog->MESSAGE = $message;
    $aLog->DATED = $date;

    $Log->save($aLog);
	}
}
?>
