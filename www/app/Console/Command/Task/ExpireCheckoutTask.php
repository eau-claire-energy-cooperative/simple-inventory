<?php
class ExpireCheckoutTask extends AppShell {
    public $uses = array('CheckoutRequest');

    public function execute($params) {

      //get a list of all checkout requests
      $requests = $this->CheckoutRequest->find('all');
      $this->out('Found ' . count($requests) . ' checkout requests');

      //figure out if any are expired
      $today = time();
      $count = 0;
      foreach($requests as $r)
      {
        //check if request is expired
        if($r['CheckoutRequest']['check_out_unix'] < $today && $r['CheckoutRequest']['check_in_unix'] < $today)
        {
          //make sure request is not active
          if($r['CheckoutRequest']['status'] != 'active')
          {
            $count ++;
            $this->out("Checkout Request ID " . $r['CheckoutRequest']['id'] . " is expired");
            $this->dblog("Checkout Request ID " . $r['CheckoutRequest']['id'] . " is expired", 'Scheduler', 'INFO');

            $this->CheckoutRequest->delete($r['CheckoutRequest']['id']);
          }
          else
          {
            $this->out("Checkout Request ID " . $r['CheckoutRequest']['id'] . " is expired but still active");
          }

        }
      }

      $this->out("Deleted " . $count . " expired requests");
    	$this->out('Complete');
    }
}
?>
