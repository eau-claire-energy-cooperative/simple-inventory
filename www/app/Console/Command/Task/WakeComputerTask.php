<?php
class WakeComputerTask extends AppShell {
    public $uses = array('Computer');

    public function execute($params) {
    	App::uses("PingComponent","Controller/Component");
    	App::uses('ComponentCollection', 'Controller');
    	
    	$collection = new ComponentCollection();
    	$pingComp = new PingComponent($collection);
    	
    	//check if computer is awake
    	$replies = $pingComp->ping($params['Computer Name']);
    	
    	if($replies['transmitted'] != $replies['received'])
    	{
    		//log
    		$this->out("Waking up " . $params['Computer Name']);
    		$this->log("Waking up " . $params['Computer Name']);
    	
    		//get the computer MAC
    		$computer = $this->Computer->find('first',array('conditions'=>array("Computer.ComputerName"=>$params['Computer Name'])));
    	
    		$pingComp->wol('10.10.10.35',$computer['Computer']['MACaddress']);
    	}
    	else
    	{
    		$this->out("Computer " . $params['Computer Name'] . " is already awake");
    	}
    }
}
?>