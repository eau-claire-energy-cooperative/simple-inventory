<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cron\CronExpression;

class ScheduleCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Checks schedule system tasks against their Cron schedule and executes them.';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $io->out('Getting a list of all scheduled commands');
		$io->out("");

    $all_schedules = $this->fetchTable('Schedule')->find('all', ['contain'=>['Command']])->all();

    foreach($all_schedules as $schedule)
		{
			$cron_exp = new CronExpression($schedule['schedule']);;

			if($cron_exp->isDue())
			{
				//run the command
				$io->out(sprintf("Running %s", $schedule['command']['name']));

				//create the parameter array for this task
				eval("\$schedule_params = " . $schedule['parameters'] . ";");

        // convert parameters into Command Options syntax (flat array)
        $options = [];
        foreach(array_keys($schedule_params) as $o)
        {
          $options[] = sprintf('--%s', strtolower(str_replace(' ', '_', $o)));
          $options[] = $schedule_params[$o];
        }

        // run the correct command
        switch($schedule['command_id']){
					case 2:
            $this->executeCommand(WakeComputerCommand::class, $options);
						break;
					case 4:
						$this->executeCommand(SendEmailCommand::class, $options);
						break;
					case 5:
						//$this->DiskSpace->execute($schedule_params);
						break;
					case 7:
					  $this->executeCommand(RemoveOldProgramsCommand::class, $options);
            break;
          case 8:
            $this->executeCommand(PurgeDecomCommand::class, $options);
            break;
          case 9:
            $this->executeCommand(PurgeLogsCommand::class, $options);
            break;
          case 10:
            //$this->CheckLifecycles->execute($schedule_params);
            break;
          case 11:
            //$this->ExpireCheckout->execute($schedule_params);
            break;
          case 12:
            //$this->LicenseExpirationReminder->execute($schedule_params);
            break;
				}
			}
			else
			{
				$io->out(sprintf("%s - Next Run: %s", $schedule['command']['name'], $cron_exp->getNextRunDate()->format('m-d-Y H:i:s')));
			}
		}

    return static::CODE_SUCCESS;
  }
}
?>
