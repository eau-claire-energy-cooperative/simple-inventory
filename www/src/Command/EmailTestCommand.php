<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class EmailTestCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Sends a test email to confirm SMTP information is correct';
  }

  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    $parser
      ->addArgument('email', [
          'required'=>true,
          'help' => 'Email address to send the test message to'
      ]);

    return $parser;
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $io->out(sprintf('Sending test email to %s', $args->getArgument('email')));
		$io->out("If the recipient does not get the email check your SMTP settings.");

    $this->sendMail("Inventory Test Email", "This is a test of the Simple Inventory SMTP settings", $args->getArgument('email'));

    return static::CODE_SUCCESS;
  }
}
?>
