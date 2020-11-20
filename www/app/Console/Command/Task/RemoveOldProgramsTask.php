<?php
class RemoveOldProgramsTask extends AppShell {
    public $uses = array('Programs');
    
    public function execute($params) {
		$found_computers = array();
		
    	//get a list of all the currently "restricted" programs
    	$all_programs = $this->Programs->find('all', array('order'=>'Programs.program'));
    	$this->out('Found ' . count($all_programs) . ' unique program entries');
    	
    	//go through and find any PC's that have been removed using these programs
    	$currentP = '';
    	$deleteList = array();
    	foreach ($all_programs as $aProgram){
    	
    		if($aProgram['Programs']['program'] != $currentP)
    		{
    		    $currentP = $aProgram['Programs']['program'];
    		    $total_computers = 0;
    		}
    		
    		if(!isset($aProgram['Computer']['ComputerName']))
    		{
    		   $deleteList[] = $aProgram['Programs']['ID'];
    		}
    		
    	}
    	
    	if(count($deleteList) > 0)
    	{
        	$this->dblog('Deleting ' . count($deleteList) . ' unneeded program entries');
        	$this->out('Deleting ' . count($deleteList) . ' unneeded program entries');
    	}
    	
    	foreach($deleteList as $id)
    	{
    	    $this->Programs->delete($id);
    	}
    	
    	$this->out('Complete');
    }
}
?>