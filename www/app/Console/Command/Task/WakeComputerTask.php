<?php
class WakeComputerTask extends AppShell {
    public $uses = array('Computer');

    public function execute($params) {
    	App::uses("PingComponent","Controller/Component");
    	App::uses('ComponentCollection', 'Controller');
    	
    	$collection = new ComponentCollection();
    	$pingComp = new PingComponent($collection);
    	
    	$this->out("Waking up " . $params['Computer Name']);
    	
    	//get the computer MAC
    	$computer = $this->Computer->find('first',array('conditions'=>array("Computer.ComputerName"=>$params['Computer Name'])));
    	
    	$this->out($computer['Computer']['MACaddress']);
    	$pingComp->wol('10.10.10.35',$computer['Computer']['MACaddress']);
    }
}
?>