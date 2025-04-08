<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class CheckDiskSpaceCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Check the disk space of all devices and email if one is outside the given threshold.';
  }

  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    // define named options required for this task
    $parser
      ->addOption('minimum_space_threshold', [
          'required'=>true,
          'help' => 'minimum disk space threshold, as a percent'
      ]);

    return $parser;
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
		$settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();

		//first, get a list of all the disks
		$disks = $this->fetchTable('Disk')->find('all', ['contain'=>['Computer']]);

		foreach($disks->all() as $aDisk){

			if(($aDisk['space_free']/$aDisk['total_space']) * 100 <= $args->getOption('minimum_space_threshold'))
			{
        $message = sprintf('Disk Space Warning on %s (less than %d%%)', $aDisk['computer']['ComputerName'], $args->getOption('minimum_space_threshold'));
        $io->out($message);
				$this->dblog($message);
			}

		}

    return static::CODE_SUCCESS;
  }
}
?>
