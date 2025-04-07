<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;

class ExpireCheckoutCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Expire checkout requests where the check-in and check-out date has passed';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    //get a list of all checkout requests
    $CheckoutRequest = $this->fetchTable('CheckoutRequest');
    $requests = $CheckoutRequest->find('all', ['contain'=>['Computer']]);
    $io->out(sprintf('Found %d checkout requests', $requests->count()));

    //figure out if any are expired
    $today =FrozenTime::now();
    $count = 0;
    foreach($requests->all() as $r)
    {
      //check if request is expired
      if($r->check_out_date->lessThan($today) && $r->check_in_date->addDays(1)->lessThan($today))
      {
        //make sure request is not active
        if($r['status'] != 'active')
        {
          $count ++;
          $io->out(sprintf("Checkout Request ID %d is expired", $r['id']));
          $this->dblog(sprintf("Checkout Request ID %d is expired", $r['id']), 'Scheduler', 'INFO');

          $CheckoutRequest->delete($r);
        }
        else
        {
          $io->out(sprintf("Checkout Request ID %d is expired but still active", $r['id']));
          $this->sendMail('Device Check In Missed',
                          sprintf('Checkout Request #%d is past check-in but device <b> %s</b> is still checked out.',
                                  $r['id'], $r['computer'][0]['ComputerName']));
        }

      }
    }

    $io->out(sprintf("Deleted %d expired requests", $count));
  	$io->out('Complete');

    return static::CODE_SUCCESS;
  }
}
?>
