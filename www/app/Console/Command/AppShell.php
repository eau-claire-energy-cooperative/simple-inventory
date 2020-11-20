<?php
/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {
	var $uses = array('User','Setting','Logs');
	
	
	public function dblog($message){
		$this->Logs->create();
		$this->Logs->set('LOGGER','Scheduler');
		$this->Logs->set('LEVEL','INFO');
		$this->Logs->set('MESSAGE',$message);
		$this->Logs->set("DATED",date("Y-m-d H:i:s",time()));
		$this->Logs->save();
	}
	
	public function sendMail($subject,$message){
		App::import("Vendor",'PHPMailer',array('file'=>'PhpMailer/class.phpmailer.php'));
		 
		//get the settings
		$settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
		
		//setup the mailer
		$email = new PHPMailer();
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
		
		//get the addresses to send to
		$users = $this->User->find('all',array('conditions'=>array('User.send_email'=>'true')));
		
		foreach($users as $aUser){
			//log email
			$this->dblog("Sending email to " . $aUser['User']['email']);
			
			$email->AddAddress($aUser['User']['email']);
		}
		 
		//send the message
		$email->Send();
		 
	}
}
