<?php

class LifecycleHelper extends AppHelper {

	function isDue($last_date, $expression){
		App::import('Vendor','Cron/CronExpression');

    $cron_exp = Cron\CronExpression::factory($expression);

		return $cron_exp->getNextRunDate($last_date)->format('U') <= time();
	}

  function getNextDate($last_date, $expression){
    App::import('Vendor','Cron/CronExpression');

    $cron_exp = Cron\CronExpression::factory($expression);

		return $cron_exp->getNextRunDate($last_date)->format('c');
  }
}
?>
