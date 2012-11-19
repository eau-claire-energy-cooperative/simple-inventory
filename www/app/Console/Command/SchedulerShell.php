<?php 

class SchedulerShell extends AppShell {
	var $uses = array('Schedule');
	var $tasks = array('RestrictedPrograms','WakeComputer');
	
	public function main(){
		App::import('Vendor','Cron/CronExpression');
		
		$this->out('Getting a list of all scheduled commands');
		$this->out("");
		
		$all_schedules = $this->Schedule->find('all');
		
		//go through each and determine if it should run or not
		foreach($all_schedules as $schedule)
		{
			$cron_exp = Cron\CronExpression::factory($schedule['Schedule']['schedule']);	
			
			if($cron_exp->isDue())
			{
				//run the command
				$this->out("Running " . $schedule['Command']['name']);
				
				//create the parameter array for this task
				eval("\$schedule_params = " . $schedule['Schedule']['parameters'] . ";");

				if($schedule['Schedule']['command_id'] == 1)
				{
					$this->RestrictedPrograms->execute($schedule_params);					
				}
				else if($schedule['Schedule']['command_id'] == 2)
				{
					$this->WakeComputer->execute($schedule_params);
				}
			}
			else
			{
				$this->out($schedule['Command']['name'] . " - Next Run: " . $cron_exp->getNextRunDate()->format('m-d-Y H:i:s'));
			}
		}
	}
	
}
?>