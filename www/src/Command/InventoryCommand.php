<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

  public function sendMail($subject, $message, $recipient = ""){
		//get the settings
		$settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();

		//setup the mailer
		$email = new PHPMailer(true);
		$email->IsSMTP();
		$email->IsHTML(true);
		$email->Host = $settings['smtp_server'];
		$email->Port = 25;
		$email->Username = $settings['smtp_user'];
		$email->Password = $settings['smtp_pass'];
		$email->SetFrom($settings['outgoing_email']);

		//setup the subject/message
		$email->Subject = $subject;
		$email->Body = $message;

    if(empty($recipient))
    {
  		//send to admin users
  		$users = $this->fetchTable('User')->find('all', ['conditions'=>['User.send_email'=>'true']])->all();

  		foreach($users as $aUser){
  			//log email
  			$this->dblog("Sending email to " . $aUser['email']);

  			$email->AddAddress($aUser['email']);
  		}
    }
    else
    {
      //recipient is already set
      $email->AddAddress($recipient);
      $this->dblog("Sending email to " . $recipient);
    }

		//send the message
		$email->Send();

	}
}
?>
