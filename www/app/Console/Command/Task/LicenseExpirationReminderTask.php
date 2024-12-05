  <?php
class LicenseExpirationReminderTask extends AppShell {
    public $uses = array('License');

    public function execute($params) {

      //get a list of all licenses that have an expiration date
      $licenses = $this->License->find('all', array('conditions'=>"License.ExpirationDate IS NOT NULL", 'order'=>array('License.LicenseName desc')));
      $this->out('Found ' . count($licenses) . ' licenses');

      // calc the reminder date for each
      $now = new DateTime();
      $send_reminders = array();

      foreach($licenses as $l){
        $reminder_date = $this->_calcReminder($l['License']['ExpirationDate'], $l['License']['StartReminder']);

        if($reminder_date < $now)
        {
          $expiration_date = new DateTime($l['License']['ExpirationDate']);
          $send_reminders[] = $l['License']['LicenseName'] . " - Expiration: " . $expiration_date->format("m/d/Y");
        }
      }

      // only send an email if there are licenses to check on
      if(count($send_reminders) > 0)
      {
        $this->out('Found ' . count($send_reminders) . ' licenses to renew');

        //create the message
        $message = "The following licenses are getting close to, or past, expiration. Reach out to the vendor to start the renewal process. <br /><br />";
        $message = $message . implode($send_reminders, "<br />");

        $message = $message . "<br /><br />";
        $this->sendMail("License Renewal Reminders", $message);
      }

    	$this->out('Complete');
    }

    private function _calcReminder($expiration, $start_reminder){
      // calc when reminders will start
      $next_reminder = new DateTime($expiration);
      $next_reminder->sub(new DateInterval('P' . $start_reminder . 'M'));

      return $next_reminder;
    }
}
?>
