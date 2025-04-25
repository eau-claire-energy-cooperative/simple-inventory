<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LicenseHelper extends Helper
{
  public $helpers = ['Time'];

  function hasExpiration($license){
    // return True if license has an expiration date set
    return $license['ExpirationDate'] != null && !empty($license['ExpirationDate']);
  }

  function isExpiring($expiration, $start_reminder){
    $result = false;

    if(!empty($expiration))
    {
      if($this->Time->isPast($this->calcReminder($expiration, $start_reminder))){
        // return true if past the start reminder time
        $result = true;
      }
    }

    return $result;
  }

	function expirationCSS($expiration, $start_reminder){
    $result = ""; // no text change by default

    if(!empty($expiration))
    {
      if($this->Time->isPast($expiration))
      {
        // red if past expiration
        $result = 'text-danger';
      }
      else if($this->Time->isPast($this->calcReminder($expiration, $start_reminder))){
        // yellow if past reminder start time
        $result = 'text-warning';
      }
    }

    return $result;
	}

  function calcReminder($expiration, $start_reminder){
    // calc when reminders will start
    $next_reminder = new \DateTime($expiration);
    $next_reminder->sub(new \DateInterval('P' . $start_reminder . 'M'));

    // Getting the new date after substration
    return $next_reminder->format('m/d/y');
  }
}
?>
