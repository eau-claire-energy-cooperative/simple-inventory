<?php
class CheckLifecyclesTask extends AppShell {
    public $uses = array('Lifecycle');

    public function execute($params) {

    	//get a list of all the programs
    	$lifecycles = $this->Lifecycle->find('all');
    	$this->out('Found ' . count($lifecycles) . ' software lifecycles');

      //check if any of these need an update
      $checkApps = array();
      foreach($lifecycles as $cycle)
      {
        if($this->isDue(date('Y-m-d', strtotime($cycle['Lifecycle']['last_check'])), $cycle['Lifecycle']['update_frequency']))
        {
          $checkApps[] = $cycle;
        }
      }

      if(count($checkApps) > 0)
      {
        $this->out('Found ' . count($checkApps) . ' applications to check');
        $message = "The following applications are due for a software lifecycle check: <br /><br />";

        foreach($checkApps as $cycle)
        {
          $message = $message . $cycle['Applications']['full_name'] . '<br />';
        }

        $this->sendMail("Software Lifecycle Check",$message);
      }

    	$this->out('Complete');
    }

    //duplicated from LifecycleHelper class
    function isDue($last_date, $expression){
  		App::import('Vendor','Cron/CronExpression');

      $cron_exp = Cron\CronExpression::factory($expression);

  		return $cron_exp->getNextRunDate($last_date)->format('U') <= time();
  	}
}
?>
