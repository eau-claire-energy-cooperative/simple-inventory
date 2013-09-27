<?php
class SendEmailsTask extends AppShell {
    public $uses = array('EmailMessage');

    public function execute($params) {
    	
		//get an email messages that need to be sent
		$messages = $this->EmailMessage->find('all');
		
		if($messages)
		{
			$this->log('Found ' . count($messages) . " messages to send");
			
			foreach($messages as $aMessage)
			{
		
				$this->sendMail($aMessage['EmailMessage']['subject'],$aMessage['EmailMessage']['message']);
				
				//delete this message
				$this->EmailMessage->delete($aMessage['EmailMessage']['id']);
			}
		}
    }
}
?>