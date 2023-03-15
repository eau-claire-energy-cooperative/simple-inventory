<?php
class RestrictedProgramsTask extends AppShell {
    public $uses = array('Applications');

    public function execute($params) {
		$found_computers = array();

    	//get a list of all the currently monitored applications
      $monitored_applications = $this->Applications->find('all', array('conditions'=>array('Applications.monitoring'=>'true'), 'order'=>'Applications.name'));

      //go through and find any PC's that are using these programs

      $total_devices = 0;
      $message = "<p>Below is a list monitored applications and the computers they were found on:</p><br><br>";

      foreach($monitored_applications as $app){
        $this->out("For application: " . $app['Applications']['name']);
        $message = $message . '<h3>' . $app['Applications']['name'] . " (v" . $app['Applications']['version']  . ")</h3>";

        if(count($app['Computer']) > 0)
        {
          $total_devices = $total_devices + count($app['Computer']);

          foreach($app['Computer'] as $comp){
            $this->out($comp['ComputerName']);
            $message = $message . "<li>" . $comp['ComputerName'] . "</li><br>";
          }

          $message = $message . "<br><br>";
        }

        $this->out("-----------");
      }

      //send the email if any devices were found
    	if($total_devices > 0)
    	{
    		$this->dblog("Monitored Applications Task found devices, compiling email");

    		$this->sendMail("Monitored Applications",$message);
    	}
    }
}
?>
