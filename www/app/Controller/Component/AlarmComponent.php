<?php
class AlarmComponent extends Component {
    var $name = "Alarm Component";
	var $alarms = array('disk_space'=>array('subject'=>'%s Disk Space Warning','message'=>'This computer has %s percent or less disk space remaining on drive %s'),
						'file'=>array('subject'=>'File Expiration Warning on %s','message'=>'File monitoring services has detected the file %s has expired'),
						'offline'=>array('subject'=>'%s Has Gone Offline','message'=>'Monitoring has detected that the computer with IP address %s has gone offline'),
						'online'=>array('subject'=>'%s Is Online','message'=>'Monitoring has detected that the computer with IP address %s is no online'),
						'service_offline'=>array('subject'=>'Service on %s has stopped','message'=>'The %s service has stopped running'),
						'service_online'=>array('subject'=>'Service on %s is running','message'=>'The %s service is now running'));
						
												
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
		
		if($this->settings['monitoring_email'] == 'true')
		{
			$this->_sendMail($subject,$message);
		}
		
		if(trim($this->settings['monitoring_script']) != '')
		{
			exec($this->settings['monitoring_script'] . ' "' . escapeshellarg($subject) . '" "' . escapeshellarg($message) . '"');
		}
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
			//log email
			$this->_log(date("Y-m-d H:i:s",time()),"API Manager","INFO","Sending email to " . $aUser['User']['email']);
			
			$email->AddAddress($aUser['User']['email']);
		}
		 
		//send the message
		$email->Send();
		 
	}
}?>