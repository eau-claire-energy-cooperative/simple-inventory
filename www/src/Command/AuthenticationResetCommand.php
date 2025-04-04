<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class AuthenticationResetCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Resets local user authentication if locked out of system.';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {
    $io->out('Resetting Local User Authentication');
		$io->out("");

		$io->out('Setting auth type to local');
		$this->fetchTable('Setting')->updateQuery()->set(['value'=>'local'])->where(['key'=>'auth_type'])->execute();

		$io->out('Resetting all user passwords to default');
    $this->fetchTable('User')->updateQuery()->set(['password'=>'1a1dc91c907325c69271ddf0c944bc72'])->execute();

		$io->out('If you still have problems logging in set your settings encryption value back to false if set to true');
		$this->dblog('Authentication Reset command run, reset all local user logins', 'CLI', 'WARNING');

    return static::CODE_SUCCESS;
  }
}
?>
