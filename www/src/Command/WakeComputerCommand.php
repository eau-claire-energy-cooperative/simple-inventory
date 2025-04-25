<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\PingComponent;

class WakeComputerCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Sends WOL packet to device based on Device Name';
  }

  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    // define named options required for this task
    $parser
      ->addOption('computer_name', [
          'required'=>true,
          'help' => 'Computer to wake up'
      ]);

    return $parser;
  }


  public function execute(Arguments $args, ConsoleIo $io): int
  {

    $pingComp = new PingComponent(new ComponentRegistry());

    $computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.ComputerName'=>$args->getOption('computer_name')]])->first();

    if($computer != null)
    {
      $replies = $pingComp->ping($computer['IPaddress']);

      if($replies['transmitted'] != $replies['received'])
      {
        $io->out(sprintf("Waking computer %s", $computer['ComputerName']));

        $pingComp->wol("10.10.10.35", $computer['MACaddress']);
      }
      else
      {
        $io->out(sprintf("Computer %s is already awake", $computer['ComputerName']));
      }
    }
    else
    {
      $io->out(sprintf("Cannot find computer %s", $computer['ComputerName']));
    }
    return static::CODE_SUCCESS;
  }
}
?>
