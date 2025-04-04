<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

/*
This class is not meant for actual use and should be extended by other CLI command classes
*/
abstract class InventoryCommand extends Command
{

  public function dblog($message, $module='Scheduler', $level='INFO'){
    $Log = $this->fetchTable('Logs');

    $aLog = $Log->newEmptyEntity();
    $aLog->LOGGER = $module;
    $aLog->LEVEL = $level;
    $aLog->MESSAGE = $message;
    $aLog->DATED = date("Y-m-d H:i:s",time());

    $Log->save($aLog);
	}
}
?>
