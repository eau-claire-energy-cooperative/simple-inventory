<?php
class PurgeDecomTask extends AppShell {
    public $uses = array('Decommissioned');

    public function execute($params) {

    	$this->out("Attempting to find devices older than " . $params['Years'] .  ' years');

      //find devices older than x years from today
      $outdated = $this->Decommissioned->find('all', array('conditions'=>array('LastUpdated <=' => date('Y-m-d', strtotime('-' . $params['Years'] . ' years'))),
                                                     'order'=>array('LastUpdated asc')));

      $message = "The following computers are older than " . $params['Years'] . " years and have been purged:<br /><br />";

      $this->out('Found ' . count($outdated) . ' devices');
      foreach($outdated as $computer){
        $this->out($computer['Decommissioned']['ComputerName'] . " : " . $computer['Decommissioned']['LastUpdated']);
        $message = $message . $computer['Decommissioned']['ComputerName'] . ' was decommissioned on ' . $computer['Decommissioned']['LastUpdated'] . "<br />";

        //delete the computer
        $this->Decommissioned->delete($computer['Decommissioned']['id']);
      }

      //send an email with the details
      $this->sendMail('Purged Computers', $message);
    }
}
?>
