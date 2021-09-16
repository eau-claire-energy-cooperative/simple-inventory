<?php

class ApiController extends AppController {
	var $layout = '';
	var $helpers = array('Js');
	var $uses = array('Computer','ComputerLogin','DeviceType','Disk','Setting','Command','Service','RestrictedProgram','Programs','Location','EmailMessage','Logs','User');
	var $json_data = null;

	function beforeFilter(){

	    //check the auth value
	    $auth_key = $this->request->header('X-Auth-Key');

	    $auth_value = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'api_auth_key')));

	    if($auth_key == $auth_value['Setting']['value'])
	    {
	       $this->json_data = $this->request->input('json_decode');
	    }
	    else
	    {
	        //show error message, stop all processing here
	        $this->set('result',array('type'=>'error','message'=>'auth key value is invalid'));
	        $this->render('api');
	        $this->response->send();
	        $this->_stop();
	    }
	}

	function index(){
		$this->layout = 'default';

		$this->set('title_for_layout','API');
	}

	function inventory($action){
		$result = array();

		if($action == "exists")
		{
			$computerName = trim($this->json_data->computer);

			//check if this computer exists
			$computer = $this->Computer->find('first',array('conditions'=>array('ComputerName'=>$computerName)));

			if($computer){
				$result['type'] = 'success';

        //set device type as a string
        $computer['Computer']['DeviceType'] = $computer['DeviceType']['slug'];

				$result['result'] = $computer['Computer'];
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'computer ' . $computerName . ' does not exist';
			}
		}
		else if($action == 'update')
		{
			//get this computer first from the DB
			$aComputer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$this->json_data->id)));

      //create in the attributes list from the device type
      $allowedAttributes = explode(",",$aComputer['DeviceType']['attributes']);

			$this->Computer->create();
			$this->Computer->id = $this->json_data->id;

      // not required to send this field in, set it if missing
      if(!isset($this->json_data->LastUpdated))
      {
        $aComputer['Computer']['LastUpdated'] = date('Y-m-d H:i:s');
      }

			//set the fields based on the attribute types for this device
      foreach($allowedAttributes as $a)
      {
        if(isset($this->json_data->$a))
        {
          $aComputer['Computer'][$a] = $this->json_data->$a;
        }
      }

      //this could fail validation
			if($this->Computer->save($aComputer))
      {
        //also add the computer login information, if that attribute exists
        if(in_array("CurrentUser", $allowedAttributes))
        {
    			$this->ComputerLogin->create();
    			$this->ComputerLogin->set('Username',$this->json_data->CurrentUser);
    			$this->ComputerLogin->set('comp_id',$this->json_data->id);
    			$this->ComputerLogin->set('LoginDate',$aComputer['Computer']['LastUpdated']);
    			$this->ComputerLogin->save();
        }

  			$result['type'] = 'success';
  			$result['message'] = 'computer ' . $this->json_data->ComputerName . ' has been updated';
      }
      else
      {
        $result['type'] = 'error';
  			$result['message'] = 'computer ' . $aComputer['Computer']['ComputerName'] . ' could not be updated';
        $result['validation_errors'] = $this->Computer->validationErrors;
      }
		}
		else if ($action == 'add')
		{
			//attempt to get the default location id and device types list
			$locations = $this->Location->find('first',array('conditions'=>array('Location.is_default'=>'true')));
      $deviceType = $this->DeviceType->find('first', array('conditions' => array("DeviceType.slug"=>$this->json_data->DeviceType)));

			if($locations)
			{
        //check that device type exists
        if($deviceType)
        {
  				$this->Computer->create();
  				$this->Computer->set('ComputerName',trim($this->json_data->ComputerName));
  				$this->Computer->set('AssetId',1);
          $this->Computer->set('DeviceType',$deviceType['DeviceType']['id']);
  				$this->Computer->set('ComputerLocation',$locations['Location']['id']);

  				$this->Computer->save();

  				$result['type'] = 'success';
  				$result['message'] = 'computer ' . $this->json_data->ComputerName . ' added to database';
  				$result['result'] = array('id'=>$this->Computer->id);
        }
        else
        {
          $result['type'] = 'error';
  				$result['message'] = 'error finding device type ' . $this->json_data->DeviceType;
        }
			}
			else
			{
				$result['type'] = 'error';
				$result['message'] = 'error finding default location';
			}

		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

  function device_type($action = 'get'){
    $result = array();

    if($action == 'get')
    {
      $types = $this->DeviceType->find('all',array('recursive'=>0,'order'=>array('DeviceType.name asc')));

      if($types)
      {
        $result['type'] = "success";
        $result['result'] = $types;
      }
      else
      {
        $result["type"] = 'error';
        $result['message'] = 'error getting device types';
      }
    }
    else
    {
      $result["type"] = 'error';
      $result['message'] = 'must call an action';
    }

    $this->set('result',$result);
    $this->render('api');
  }

	function send_email(){
		$result = array();

		$subject = $this->json_data->subject;
		$message = $this->json_data->message;

		if(isset($subject) && isset($message))
		{
			$this->EmailMessage->create();
			$this->EmailMessage->set('subject',$subject);
			$this->EmailMessage->set('message',$message);
			$this->EmailMessage->save();

			$result['type'] = 'success';
			$result['message'] = 'sending email ' . $subject;
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'need a subject and message content to send';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function disk($action = 'get'){
		$result = array();

		if($action == 'get')
		{
			$disks = $this->Disk->find('all',array('conditions'=>array('Disk.comp_id'=>$this->json_data->comp_id),'order'=>array('Disk.label')));

			if($disks)
			{
				$result['type'] = "success";
				$result['result'] = $disks;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'error getting disks';
			}
		}
		else if($action == 'update')
		{
			$aDisk = $this->Disk->find('first',array('conditions'=>array('Disk.label'=>$this->json_data->label,'Disk.comp_id'=>$this->json_data->comp_id)));

			if($aDisk)
			{
				//update the disk
				$aDisk['Disk']['total_space'] = $this->json_data->total_space;
				$aDisk['Disk']['space_free'] = $this->json_data->space_free;
				$aDisk['Disk']['type'] = $this->json_data->type;

				$this->Disk->save($aDisk);

				$result['type'] = "success";
				$result['message'] = 'Disk updated for computer ' . $this->json_data->comp_id;
			}
			else
			{
				//create a new disk entry
				$this->Disk->create();
				$this->Disk->set('comp_id',$this->json_data->comp_id);
				$this->Disk->set('label',$this->json_data->label);
				$this->Disk->set('total_space',$this->json_data->total_space);
				$this->Disk->set('space_free',$this->json_data->space_free);
				$this->Disk->set('type',$this->json_data->type);
				$this->Disk->save();

				$result["type"] = 'success';
				$result['message'] = 'Disk added for computer ' . $this->json_data->comp_id;
			}
		}
		else if($action == 'delete')
		{
			//delete
			$this->Disk->delete($this->json_data->id);

			$result["type"] = 'success';
			$result['message'] = 'Disk deleted';
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function location($action = 'get'){
		$result = array();

		if($action == 'get')
		{
			$locations = $this->Location->find('all',array('recursive'=>0,'order'=>array('Location.location')));

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
		else if($action == 'default')
		{
			$default_location = $this->Location->find('first',array('conditions'=>array('Location.is_default'=>'true'),'order'=>array('Location.location')));

			if($default_location)
			{
				$result['type'] = "success";
				$result['result'] = $default_location['Location'];
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'error getting default location';
			}
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function add_log(){
		$result = array();

		$this->_log($this->json_data->date,$this->json_data->logger,$this->json_data->level,$this->json_data->message);

		$result['type'] = 'success';
		$this->set('result',$result);
		$this->render('api');
	}

	function programs($action){
		$result = array();

		if($action == 'get')
		{
			$computerId = $this->json_data->id;

			//set recursive to one so that joined tables aren't pulled
			$programs = $this->Programs->find('all',array('conditions' => array('comp_id' => $computerId), 'order' => array('program ASC'),'recursive'=>-1));

			if($programs)
			{
				$result['type'] = "success";
				$result['result'] = $programs;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'cannot find programs for computer id ' . $computerId;
			}
		}
		else if($action == 'clear')
		{
			$computerId = $this->json_data->id;

			if(isset($computerId))
			{
				$this->Programs->query("delete from programs where comp_id = " . $computerId);

				$result['type'] = 'success';
				$result['result'] = "Programs deleted for computer id " . $computerId;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'cannot delete programs computer id needed';

			}
		}
		else if($action == 'add')
		{
			$this->Programs->create();
			$this->Programs->set('program',$this->json_data->program);
			$this->Programs->set('version',$this->json_data->version);
			$this->Programs->set('comp_id',$this->json_data->id);
			$this->Programs->save();

			$result['type'] = 'success';
			$result['result'] = "Programs added for computer id " . $this->json_data->id;
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function services($action){
		$result = array();

		if($action == 'get')
		{
			$computerId = $this->json_data->id;

			//set recursive to one so that joined tables aren't pulled
			$services = $this->Service->find('all',array('conditions' => array('comp_id' => $computerId), 'order' => array('name ASC'),'recursive'=>-1));

			if($services)
			{
				$result['type'] = "success";
				$result['result'] = $services;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'cannot find services for computer id ' . $computerId;
			}
		}
		else if($action == 'clear')
		{
			$computerId = $this->json_data->id;

			if(isset($computerId))
			{
				$this->Service->query("delete from services where comp_id = " . $computerId);

				$result['type'] = 'success';
				$result['result'] = "Services deleted for computer id " . $computerId;
			}
			else
			{
				$result["type"] = 'error';
				$result['message'] = 'cannot delete services computer id needed';

			}
		}
		else if($action == 'add')
		{
			$this->Service->create();
			$this->Service->set('name',$this->json_data->name);
			$this->Service->set('startmode',$this->json_data->mode);
			$this->Service->set('status',$this->json_data->status);
			$this->Service->set('comp_id',$this->json_data->id);
			$this->Service->save();

			$result['type'] = 'success';
			$result['result'] = "Service added for computer id " . $this->json_data->id;
		}
		else if($action == 'update')
		{
			$existingService = $this->Service->find('first',array('conditions'=>array('Service.name'=>$this->json_data->name,'Service.comp_id'=>$this->json_data->id)));

			if($existingService)
			{
				$existingService['Service']['startmode'] = $this->json_data->mode;
				$existingService['Service']['status'] = $this->json_data->status;

				$this->Service->save($existingService);

				$result['type'] = 'success';
				$result['result'] = $this->json_data->name . ' updated';
			}
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function settings($action = 'get'){
		$result = array();

		if($action == 'get')
		{
			$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value'),'order'=>array('Setting.key')));

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
		}
		else
		{
			$result["type"] = 'error';
			$result['message'] = 'must call an action';
		}

		$this->set('result',$result);
		$this->render('api');
	}

	function _log($date,$logger,$level,$message){

		$this->Logs->create();
		$this->Logs->set('DATED',$date);
		$this->Logs->set('LOGGER',$logger);
		$this->Logs->set('LEVEL',$level);
		$this->Logs->set('MESSAGE',$message);
		$this->Logs->save();
	}
}

?>
