<?php
class RestrictedProgramsTask extends Shell {
    public $uses = array('Programs','RestrictedProgram');
    
    public function execute($params) {
	
    	//get a list of all the currently "restricted" programs
    	$restricted_programs = $this->RestrictedProgram->find('all',array('order'=>'RestrictedProgram.name'));
    	
    	//go through and find any PC's that are using these programs
    	foreach ($restricted_programs as $rProgram){
    		$this->out("For Program: " . $rProgram['RestrictedProgram']['name']);
    		
    		$programs = $this->Programs->find('all',array('conditions' => array('Programs.program LIKE "' . $rProgram['RestrictedProgram']['name'] . '%"')));
    		
    		
    		foreach($programs as $computer){
    			$this->out($computer['Computer']['ComputerName']);
    		}
    		
    		$this->out("-----------");
    	}
    }
}
?>