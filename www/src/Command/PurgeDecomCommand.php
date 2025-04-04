<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class PurgeDecomCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Removes devices from the decommissioned table older than X years';
  }

  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    // define named options required for this task
    $parser
      ->addOption('years', [
          'required'=>true,
          'help' => 'How many years to keep'
      ]);

    return $parser;
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $io->out(sprintf("Attempting to find devices older than %d years", $args->getOption('years')));

    //find devices older than x years from today
    $Decommissioned = $this->fetchTable('Decommissioned');
    $outdated = $Decommissioned->find('all', ['conditions'=>['LastUpdated <=' => date('Y-m-d', strtotime('-' . $args->getOption('years'). ' years'))],
                                              'order'=>['LastUpdated asc']]);

    $message = sprintf("The following computers are older than %d years and have been purged:<br /><br />",  $args->getOption('years'));

    $io->out(sprintf('Found %d devices', $outdated->count()));
    foreach($outdated->all() as $computer){
      //print and log the deletion
      $io->out(sprintf("%s: %s",$computer['ComputerName'], $computer['LastUpdated']));
      $this->dblog(sprintf('%s has been permanently deleted', $computer['ComputerName']));

      $message = $message . sprintf('%s was decommissioned on %s <br />', $computer['ComputerName'], $computer['LastUpdated']);

      //delete the computer
      $Decommissioned->delete($computer);
    }

    //send an email with the details
    $this->sendMail('Purged Computers', $message);

    return static::CODE_SUCCESS;
  }
}
?>
