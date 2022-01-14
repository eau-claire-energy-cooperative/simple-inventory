<?php
class PurgeLogsTask extends AppShell {
    public $uses = array('Logs', 'ComputerLogin');

    public function execute($params) {

    	$this->out("Attempting to find logs older than " . $params['Years'] .  ' years');

      //find devices older than x years from today
      $oldLogs = $this->Logs->find('all', array('conditions'=>array('DATED <=' => date('Y-m-d', strtotime('-' . $params['Years'] . ' years'))),
                                                     'order'=>array('DATED asc')));

      $this->out('Found ' . count($oldLogs) . ' logs that need to be deleted');
      foreach($oldLogs as $aLog){
        $this->Logs->delete($aLog['Logs']['id']);
      }

      //delete computer login logs as well
      $oldLogins = $this->ComputerLogin->find('all', array('conditions'=>array('ComputerLogin.LoginDate <=' => date('Y-m-d', strtotime('-' . $params['Years'] . ' years'))),
                                                     'order'=>array('ComputerLogin.LoginDate')));

      $this->out('Found ' . count($oldLogins) . ' login records that need to be deleted');
      foreach($oldLogins as $aLog){
       $this->ComputerLogin->delete($aLog['ComputerLogin']['id']);
      }

      //send an email with the details
      $this->dblog('Logs purged older than ' . $params['Years'] . ' years');
    }
}
?>
