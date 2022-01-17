<?php
class RemoveOldProgramsTask extends AppShell {
    public $uses = array('Applications');

    public function execute($params) {
		$found_computers = array();

    	//get a list of all the programs
    	$applications = $this->Applications->find('all');
    	$this->out('Found ' . count($applications) . ' unique applications');

      $total_deleted = 0;
      foreach($applications as $app)
      {
        //delete if no devices are currently assigned and lifecycle does not exist
        if(count($app['Computer']) == 0 && $app['Lifecycle']['id'] == NULL)
        {
          $total_deleted ++;
          $this->out("No devices assigned to application: " . $app['Applications']['name']);
          $this->Applications->delete($app['Applications']['id']);
        }
      }

    	if($total_deleted > 0)
    	{
        	$this->dblog('Deleting ' . $total_deleted . ' unneeded applications');
        	$this->out('Deleted ' . $total_deleted . ' unneeded applications');
    	}

    	$this->out('Complete');
    }
}
?>
