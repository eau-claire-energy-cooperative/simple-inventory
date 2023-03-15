<?php

class SearchController extends AppController {
	var $uses = array("Applications","Computer","Location","Service");
	var $helpers = array('Html','Csv','DiskSpace','Time');
	var $search_types = array(array("name"=>"Tag","field"=>"Computer.ComputerLocation"),
						array('name'=>'Model','field'=>'Computer.Model'),
						array('name'=>'OS','field'=>'Computer.OS'),
						array('name'=>'Memory','field'=>'Computer.Memory'),
						array('name'=>'Monitors','field'=>'Computer.NumberOfMonitors'),
            array('name'=>'Device Type', 'field'=>'DeviceType.name'));
	var $components = array('RequestHandler','Session');

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
				$this->redirect(array('controller'=>'inventory','action'=>'login'));
			}
		}
	}

	function beforeRender(){
	  parent::beforeRender();
    $this->set('requiredAttributes', $this->DEVICE_ATTRIBUTES['REQUIRED']);
    $this->set('allAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));
		$this->set('locations',$this->Location->find('list',array('fields'=>array('Location.id','Location.location'))));
	}

	function search($type,$q){
		$this->set("title_for_layout","Search Results");

		//get the type
		$type = $this->search_types[$type];

		$this->set("q",$type['name']);
		$this->set("results", $this->Computer->find('all',array('conditions' => array($type['field'] => $q),'order'=>'Computer.ComputerName')));
	}

	function listAll(){
		$this->set('title_for_layout',"List All");

		$this->set('q',"All");
		$this->set("results",$this->Computer->find('all',array('order'=>array('Computer.ComputerName'))));

		$this->render('search');
	}

	function searchApplication($app_id){
		$this->set("title_for_layout","Search Results");

		//get all computers that match the program name
    $application = $this->Applications->find('first',array('conditions' => array('Applications.id'=>$app_id)));
		$this->set("q","For Application '" . $application['Applications']['name'] . "'");

    //need to put this in a format the view can handle
    $result = array();
    foreach($application['Computer'] as $comp){
      $result[] = array('Computer'=>$comp);
    }

		$this->set('results', $result);

		$this->render('search');
	}

	function searchService($service){
		$this->set("title_for_layout","Search Results");

		//get all computers that match the program name
		$this->set("q","For Service '" . $service . "'");
		$this->set('results', $this->Service->find('all',array('conditions' => array('Service.name LIKE "' . $service . '%"') )));
	}
}
?>
