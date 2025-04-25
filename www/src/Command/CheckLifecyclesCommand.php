<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cron\CronExpression;

class CheckLifecyclesCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Check to see which software lifecycles currently need to be reviewed.';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    //get a list of all the programs
  	$lifecycles = $this->fetchTable('Lifecycle')->find('all', ['contain'=>['Application']]);
  	$io->out(sprintf('Found %d software lifecycles', $lifecycles->count()));

    //check if any of these need an update
    $checkApps = [];
    foreach($lifecycles as $cycle)
    {
      if($this->isDue($cycle['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $cycle['update_frequency']))
      {
        $checkApps[] = $cycle;
      }
    }

    if(count($checkApps) > 0)
    {
      $io->out(sprintf('Found %d applications to check', count($checkApps)));
      $message = "The following applications are due for a software lifecycle check: <br /><br />";

      foreach($checkApps as $cycle)
      {
        $message = sprintf('%s %s <br />', $message, $cycle['application']['full_name']);
      }

      $this->sendMail("Software Lifecycle Check", $message);
    }

  	$io->out('Complete');

    return static::CODE_SUCCESS;
  }

  //duplicated from LifecycleHelper class
  function isDue($last_date, $expression){
    $cron_exp = new CronExpression($expression);

		return $cron_exp->getNextRunDate($last_date)->format('U') <= time();
	}
}
?>
