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

    function versionCompare($v1, $v2){
      // break the string on version notation
      $array1 = explode(".", $v1);
      $array2 = explode(".", $v2);
      $len1 = count($array1);
      $len2 = count($array2);

      // pad each to make the same array length
      if($len1 > $len2)
      {
        for($i = $len2; $i < $len1; $i ++)
        {
          $array2[] = "0";
        }
      }
      else if ($len2 > $len1)
      {
        for($i = $len1; $i < $len2; $i ++)
        {
          $array1[] = "0";
        }
      }

      // go through and find the highest value
      for($i = 0; $i < count($array1); $i++)
      {
        if((int)$array1[$i] > (int)$array2[$i])
        {
          return 1;
        }
        else if((int)$array2[$i] > (int)$array1[$i])
        {
          return -1;
        }
      }

      return 0;
    }
}
?>
