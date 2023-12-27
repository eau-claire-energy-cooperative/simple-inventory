<?php

class SearchController extends AppController {
	var $uses = array("Applications","Computer","Location","Service","Setting");
	var $helpers = array('Html','Csv','DiskSpace','Time');
	var $search_types = array(array("name"=>"Tag","field"=>"Computer.ComputerLocation"),
						array('name'=>'Model','field'=>'Computer.Model'),
						array('name'=>'OS','field'=>'Computer.OS'),
						array('name'=>'Memory','field'=>'Computer.Memory'),
						array('name'=>'Monitors','field'=>'Computer.NumberOfMonitors'),
            array('name'=>'Device Type', 'field'=>'DeviceType.name'),
            array('name'=>'Checkout Enabled', 'field'=>'Computer.CanCheckout', 'active_menu'=>'checkout'));
	var $components = array('RequestHandler','Session');

	public function beforeFilter(){
	  $this->_check_authenticated();
	}

	function beforeRender(){
	  parent::beforeRender();
    $this->set('requiredAttributes', $this->DEVICE_ATTRIBUTES['REQUIRED']);
    $this->set('allAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));
		$this->set('locations',$this->Location->find('list',array('fields'=>array('Location.id','Location.location'))));

    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
    $this->set('settings',$settings);
	}

	function search($type,$q){
		$this->set("title_for_layout","Search Results");
    $this->_getDisplaySettings();

		//get the type
		$type = $this->search_types[$type];

    if(isset($type['active_menu']))
    {
      $this->set('active_menu', $type['active_menu']);
    }

		$this->set("q",$type['name']);
		$this->set("results", $this->Computer->find('all',array('conditions' => array($type['field'] => $q),'order'=>'Computer.ComputerName')));
	}

	function listAll(){
		$this->set('title_for_layout',"List All");
    $this->_getDisplaySettings();

		$this->set('q',"All");
		$this->set("results",$this->Computer->find('all',array('order'=>array('Computer.ComputerName'))));

		$this->render('search');
	}

	function searchApplication($app_id){
		$this->set("title_for_layout","Search Results");
    $this->_getDisplaySettings();

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

  function _getDisplaySettings(){
    # get the display settings
    $displaySetting = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'home_attributes')));
    $displayAttributes = explode(",",$displaySetting['Setting']['value']);
    $this->set('displayAttributes', $displayAttributes);

    # set the attribute names
    $columnNames = array("CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID", "Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors", "AppUpdates"=>"Application Updates", "IPAddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACAddress"=>"MAC Address");
    $this->set('columnNames', $columnNames);
  }
}
?>
