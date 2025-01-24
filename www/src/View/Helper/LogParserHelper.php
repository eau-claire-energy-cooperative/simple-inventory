<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LogParserHelper extends Helper
{
  public $helpers = ['Html'];

  function parseMessage($inventory, $message){

    //try and find a device name in this string
    foreach(array_keys($inventory) as $aKey)
    {
      $pos = strpos($message, $aKey);

      if($pos !== false)
      {
        $message = substr_replace($message, $this->Html->link($aKey,'/inventory/moreInfo/' . $inventory[$aKey]), $pos, strlen($aKey));
      }

    }

    return $message;
  }
}
?>
