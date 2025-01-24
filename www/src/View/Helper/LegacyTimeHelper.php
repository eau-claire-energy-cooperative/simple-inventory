<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LegacyTimeHelper extends Helper
{
  public function niceShort($date){
    if($date->isToday())
    {
      return sprintf("Today, %s", $date->format('H:i'));
    }
    else if($date->isYesterday())
    {
      return sprintf("Yesterday, %s", $date->format('H:i'));
    }
    else if($date->wasWithinLast('7 days'))
    {
      return $date->format('D M j, H:i');
    }
    else if($date->isThisYear())
    {
      return $date->format('M jS, H:i');
    }

    return $date->format('M jS Y, H:i');
  }
}
?>
