<?php
namespace App\View\Helper;

use Cake\View\Helper;

class OperatingSystemHelper extends Helper
{
  public $helpers = ['Time'];

	function eolCSS($eolDate){
    $result = ""; // no text change by default

    if(!empty($eolDate))
    {
      if($this->Time->isPast($eolDate))
      {
        // red if past expiration
        $result = 'text-danger';
      }
      else if($this->Time->isThisYear($eolDate)){
        // yellow if within next year
        $result = 'text-warning';
      }
    }

    return $result;
	}
}
?>
