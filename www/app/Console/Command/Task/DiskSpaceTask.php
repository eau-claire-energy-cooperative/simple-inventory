<?php
class DiskSpaceTask extends AppShell {
    public $uses = array('Computer','Disk');
	
	var $settings = null;
	
    public function execute($params) {
    	//setup the alarm component
    	App::uses("AlarmComponent","Controller/Component");
		App::uses('ComponentCollection', 'Controller');
		$collection = new ComponentCollection();
    	$this->Alarm = new AlarmComponent($collection);
		
		$this->settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));

		//first, get a list of all the computers
		$computerList = $this->Computer->find('all');
		
		if($computerList)
		{
			foreach($computerList as $computer){
						
				//also check disk space
				foreach($computer['Disk'] as $aDisk)
				{
					if(($aDisk['space_free']/$aDisk['total_space']) * 100 <= $params['Minimum Space Threshold'])
					{
						$this->log($computer['Computer']['ComputerName'] . ' Disk Space Warning');
						$this->Alarm->triggerAlarm($computer['Computer']['id'],'disk_space',$params['Minimum Space Threshold'] . "," . $aDisk['label']);
					}
				}
			}	
		}
		
    }
}
?>