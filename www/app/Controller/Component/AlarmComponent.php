<?php
class AlarmComponent extends Component {
    var $name = "Alarm Component";
	var $alarms = array('disk_space'=>array('subject'=>'%s Disk Space Warning','message'=>'This computer has %s percent or less disk space remaining on drive %s'));
						
												
	var $settings = null;
	
	function __construct(){
		$this->User = ClassRegistry::init('User');
		$this->Computer = ClassRegistry::init('Computer');
		$this->Setting = ClassRegistry::init('Setting');
		
		$this->settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
	}

	function triggerAlarm($computerId,$type,$note){
		
		//get the computer referenced by the id
		$computer = $this->Computer->find('first',array('conditions'=>array('Computer.id'=>$computerId)));
		
		//get the alarm messages
		$subject = $this->alarms[$type]['subject'];
		$message = $this->alarms[$type]['message'];
		
		//insert content
		$subject = sprintf($subject,$computer['Computer']['ComputerName']);
		$message = vsprintf($message,explode(',',$note));		
		
		$this->_sendMail($subject,$message);
	}
	
	function _sendMail($subject,$message){
		App::import("Vendor",'PHPMailer',array('file'=>'PhpMailer/class.phpmailer.php'));
		 
		//setup the mailer
		$email = new PHPMailer();
		$email->IsSMTP();
		$email->IsHTML(true);
		$email->Host = $this->settings['smtp_server'];
		$email->Port = 25;
		$email->Username = $this->settings['smtp_user'];
		$email->Password = $this->settings['smtp_pass'];
		$email->SetFrom($this->settings['outgoing_email']);
		
		//setup the subject/message
		$email->Subject = $subject;
		$email->Body = $message;
		
		//get the addresses to send to
		$users = $this->User->find('all',array('conditions'=>array('User.send_email'=>'true')));
		
		foreach($users as $aUser){
			
			$email->AddAddress($aUser['User']['email']);
		}
		 
		//send the message
		$email->Send();
		 
	}
}?>