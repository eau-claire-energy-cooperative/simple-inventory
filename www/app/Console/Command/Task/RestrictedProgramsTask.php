<?php
class RestrictedProgramsTask extends AppShell {
    public $uses = array('Programs','RestrictedProgram');
    
    public function execute($params) {
		$found_computers = array();
		
    	//get a list of all the currently "restricted" programs
    	$restricted_programs = $this->RestrictedProgram->find('all',array('order'=>'RestrictedProgram.name'));
    	
    	//go through and find any PC's that are using these programs
    	foreach ($restricted_programs as $rProgram){
    		$this->out("For Program: " . $rProgram['RestrictedProgram']['name']);
    		
    		$programs = $this->Programs->find('all',array('conditions' => array('Programs.program LIKE "' . $rProgram['RestrictedProgram']['name'] . '%"')));

    		if(count($programs) > 0)
    		{
    			$found_computers[$rProgram['RestrictedProgram']['name'] ] =  $programs;
    			
    			foreach($programs as $computer){
    				$this->out($computer['Computer']['ComputerName']);
    			}
    		
    		}
    		
    		$this->out("-----------");
    	}
    	
    	if(count($found_computers) > 0)
    	{
    		$this->dblog("Restricted Programs Task found programs, compiling email");
    		
    		$message = "<p>Below is a list restricted programs and the computers they were found on:</p><br><br>";
    		
    		//prepare a message to email the administrator
    		$keys = array_keys($found_computers);
    		foreach($keys as $aKey){
    			$message = $message . '<h3>' . $aKey . "</h3>";
    			
    			foreach($found_computers[$aKey] as $computer)
    			{
    				//catch for computers that have been deleted
    				if(isset($computer['Computer']['ComputerName']))
    				{
    					$message = $message . $computer['Computer']['ComputerName'] . "<br>";
    				} 
    			}
    			
    			$message = $message . "<br><br>";
    		}

    		$this->sendMail("Restricted Programs",$message);
    	}
    }
}
?>