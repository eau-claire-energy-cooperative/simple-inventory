<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class PurgeLogsCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Removes logs from the database older than a set time';
  }

  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    // define named options required for this task
    $parser
      ->addOption('years', [
          'required'=>true,
          'help' => 'How many years to keep logs'
      ]);

    return $parser;
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $io->out(sprintf("Attempting to find logs older than %d years", $args->getOption('years')));

    //find devices older than x years from today
    $Logs = $this->fetchTable('Logs');
    $oldLogs = $Logs->find('all', ['conditions'=>['DATED <=' => date('Y-m-d', strtotime('-' . $args->getOption('years') . ' years'))],
                                   'order'=>['DATED asc']]);

    $io->out(sprintf('Found %d logs that need to be deleted', $oldLogs->count()));
    foreach($oldLogs->all() as $aLog){
      $Logs->delete($aLog);
    }

    //delete computer login logs as well
    $ComputerLogin = $this->fetchTable('ComputerLogin');
    $oldLogins = $ComputerLogin->find('all', ['conditions'=>['ComputerLogin.LoginDate <=' => date('Y-m-d', strtotime('-' . $args->getOption('years') . ' years'))],
                                              'order'=>['ComputerLogin.LoginDate']]);

    $io->out(sprintf('Found %d login records that need to be deleted', $oldLogins->count()));
    foreach($oldLogins->all() as $aLog){
      $ComputerLogin->delete($aLog);
    }

    //send an email with the details
    $this->dblog(sprintf('Logs purged older than %d years', $args->getOption("years")));

    return static::CODE_SUCCESS;
  }
}
?>
