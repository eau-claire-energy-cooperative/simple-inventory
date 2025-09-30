<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class SendEmailCommand extends InventoryCommand
{
  public static function getDescription(): string
  {
      return 'Sends any emails in the email queue';
  }

  public function execute(Arguments $args, ConsoleIo $io): int
  {

    // check if emails should be silenced
    $silenceEmail = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'smtp_silence_mail_send']])->first();

    $EmailMessage = $this->fetchTable('EmailMessage');

    //get any email messages that need to be sent
		$messages = $EmailMessage->find('all');

		if($messages->count() > 0)
		{
			$this->dblog(sprintf("Found %d messages to send", $messages->count()));

			foreach($messages->all() as $aMessage)
			{
        if($silenceEmail['value'] != 'true')
        {
				  $this->sendMail($aMessage['subject'],$aMessage['message'],$aMessage['recipient']);
        }

				//delete this message
				$EmailMessage->delete($aMessage);
			}
		}

    return static::CODE_SUCCESS;
  }
}
?>
