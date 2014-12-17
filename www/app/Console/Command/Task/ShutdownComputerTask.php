<?php
App::uses("PingComponent","Controller/Component");
App::uses('ComponentCollection', 'Controller');

class ShutdownComputerTask extends AppShell {
    public $uses = array('Computer','Setting');

    public function execute($params) {
    	//pull in the system settings
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
    	
    	$collection = new ComponentCollection();
    	$pingComp = new PingComponent($collection);
		
		//log
    	$this->out("Shutting Down " . $params['Computer Name']);
    	$this->log("Shutting Down " . $params['Computer Name']);
		
		$pingComp->shutdown_computer($params['Computer Name'],$params['Restart'], $settings);
    }
}
?>