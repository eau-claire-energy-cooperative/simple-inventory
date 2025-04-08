<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;

class LicenseExpirationReminderCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Send a reminder if a license is within the expiration window';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    //get a list of all licenses that have an expiration date
    $licenses = $this->fetchTable('License')->find('all', ['conditions'=>["License.ExpirationDate IS NOT NULL"],
                                                           'order'=>['License.LicenseName desc']]);
    $io->out(sprintf('Found %d licenses', $licenses->count()));

    // calc the reminder date for each
    $now = FrozenTime::now();
    $send_reminders = [];

    foreach($licenses->all() as $l){
      $reminder_date = $this->_calcReminder($l['ExpirationDate'], $l['StartReminder']);

      if($reminder_date->lessThan($now))
      {
        $send_reminders[] = sprintf('%s - Expiration %s', $l['LicenseName'], $l['ExpirationDate']->format("m/d/Y"));
      }
    }

    // only send an email if there are licenses to check on
    if(count($send_reminders) > 0)
    {
      $io->out(sprintf('Found %d licenses to renew', count($send_reminders)));

      //create the message
      $message = "The following licenses are getting close to, or past, expiration. Reach out to the vendor to start the renewal process. <br /><br />";
      $message = $message . implode("<br />", $send_reminder);

      $message = $message . "<br /><br />";
      $this->sendMail("License Renewal Reminders", $message);
    }

  	$io->out('Complete');

    return static::CODE_SUCCESS;
  }

  private function _calcReminder($expiration, $start_reminder){
    // calc when reminders will start (add a negative number)
    $next_reminder = $expiration->addMonths($start_reminder * -1);

    return $next_reminder;
  }
}
?>
