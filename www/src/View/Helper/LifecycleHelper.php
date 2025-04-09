<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cron\CronExpression;

class LifecycleHelper extends Helper
{
  function isDue($last_date, $expression){
    $cron_exp = new CronExpression($expression);

		return $cron_exp->getNextRunDate($last_date)->format('U') <= time();
	}

  function getNextDate($last_date, $expression){
    $cron_exp = new CronExpression($expression);

		return $cron_exp->getNextRunDate($last_date)->format('c');
  }

  function countDue($lifecycles){
    $result = 0;

    foreach($lifecycles as $l){
      if($this->isDue($l['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $l['update_frequency']))
      {
        $result ++;
      }
    }

    return $result;
  }
}
?>
