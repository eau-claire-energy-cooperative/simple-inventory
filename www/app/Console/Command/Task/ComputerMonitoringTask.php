<?php
class ComputerMonitoringTask extends AppShell {
    public $uses = array('Computer');

    public function execute($params) {
		
		//first check on any computers that need monitoring
		$computerList = $this->Computer->find('all',array('conditions'=>array('Computer.EnableMonitoring'=>'true')));
		
		if($computerList)
		{
			foreach($computerList as $computer){
				
				if(date('U',strtotime($computer['Computer']['LastUpdated'])) < time() - 345)
				{
					if($computer['Computer']['IsAlive'] == 'true')
					{
						//computer has gone offline, alert the admin and set to false
						$this->Computer->create();
						$this->Computer->id = $computer['Computer']['id'];
						$computer['Computer']['IsAlive'] = 'false';
						$this->Computer->save($computer);
						
						$this->log('Computer ' . $computer['Computer']['ComputerName'] . ' has gone offline');
						$this->sendMail($computer['Computer']['ComputerName'] . ' Has Gone Offline','Monitoring has detected that the computer: ' . $computer['Computer']['ComputerName'] . ' with IP address: ' . $computer['Computer']['IPaddress'] . ' has gone offline.');
					}	
				}
				else
				{
					if($computer['Computer']['IsAlive'] == 'false')
					{
						//computer has come online, alert admins and set to true
						$this->Computer->create();
						$this->Computer->id = $computer['Computer']['id'];
						$computer['Computer']['IsAlive'] = 'true';
						$this->Computer->save($computer);
						
						$this->log('Computer ' . $computer['Computer']['ComputerName'] . ' has come online');
						$this->sendMail($computer['Computer']['ComputerName'] . ' Is Online','Monitoring has detected that the computer: ' . $computer['Computer']['ComputerName'] . ' with IP address: ' . $computer['Computer']['IPaddress'] . ' is now online.');
						
					}		
				}
			}
		}
    }
}
?>