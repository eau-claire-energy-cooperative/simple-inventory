<?php

class LicenseHelper extends AppHelper {

  function hasExpiration($license){
    // return True if license has an expiration date set
    return !empty($license['License']['ExpirationDate']);
  }

	function expirationCSS($expiration, $start_reminder){
		App::uses('TimeHelper', 'View/Helper');
    $timeHelper = new TimeHelper($this->_View);

    $result = ""; // no text change by default

    if(!empty($expiration))
    {
      if($timeHelper->isPast($expiration))
      {
        // red if past expiration
        $result = 'text-danger';
      }
      else if($timeHelper->isPast($this->calcReminder($expiration, $start_reminder))){
        // yellow if past reminder start time
        $result = 'text-warning';
      }
    }

    return $result;
	}

  function calcReminder($expiration, $start_reminder){
    // calc when reminders will start
    $next_reminder = new DateTime($expiration);
    $next_reminder->sub(new DateInterval('P' . $start_reminder . 'M'));

    // Getting the new date after substration
    return $next_reminder->format('m/d/Y');
  }
}
?>
