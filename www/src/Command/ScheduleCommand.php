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
				$schedule_params = json_decode($schedule['parameters'], true);

        // convert parameters into Command Arguments
        $options = [];
        foreach(array_keys($schedule_params) as $o)
        {
          $options[strtolower(str_replace(' ', '_', $o))] = $schedule_params[$o];
        }

        // run the correct command
        switch($schedule['command']['slug']){
					case "wake_computer":
            $this->executeCommand(WakeComputerCommand::class, $options);
						break;
					case "send_emails":
						$this->executeCommand(SendEmailCommand::class, $options);
						break;
					case "check_disk_space":
						$this->executeCommand(CheckDiskSpaceCommand::class, $options);
						break;
					case "remove_old_applications":
					  $this->executeCommand(RemoveOldProgramsCommand::class, $options);
            break;
          case "purge_decommissioned_devices":
            $this->executeCommand(PurgeDecomCommand::class, $options);
            break;
          case "purge_logs":
            $this->executeCommand(PurgeLogsCommand::class, $options);
            break;
          case "lifecycle_update_check":
            $this->executeCommand(CheckLifecyclesCommand::class, $options);
            break;
          case "purge_checkout_requests":
            $this->executeCommand(ExpireCheckoutCommand::class, $options);
            break;
          case "license_renewal_reminders":
            $this->executeCommand(LicenseExpirationReminderCommand::class, $options);
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
