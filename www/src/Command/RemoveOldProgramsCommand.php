<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class RemoveOldProgramsCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Removes applications that are no longer tied to any device or lifecycle';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $Application = $this->fetchTable('Application');
    $found_computers = [];

  	//get a list of all the programs
  	$applications = $Application->find('all', ['contain'=>['Computer', 'Lifecycle']]);
  	$io->out(sprintf('Found %d unique applications', $applications->count()));

    $total_deleted = 0;
    foreach($applications->all() as $app)
    {
      //delete if no devices are currently assigned and lifecycle does not exist
      if(count($app['computer']) == 0 && !isset($app['lifecycle']))
      {
        $total_deleted ++;
        $io->out(sprintf("No devices assigned to application: %s", $app['name']));
        $Application->delete($app);
      }
    }

  	if($total_deleted > 0)
  	{
      	$this->dblog(sprintf('Deleted %d unneeded applications', $total_deleted));
      	$io->out(sprintf('Deleted %d unneeded applications', $total_deleted));
  	}

  	$io->out('Complete');

    return static::CODE_SUCCESS;
  }
}
?>
