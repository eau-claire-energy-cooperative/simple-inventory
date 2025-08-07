<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LogParserHelper extends Helper
{
  public $helpers = ['Html'];
  private $link_types = ["device"=> "/inventory/more-info/%d",
                         "history"=> "/inventory/view-history/%d",
                         "application"=> "/search/searchApplication/%d",
                         "decom"=> "/inventory/more-info-decommissioned/%d",
                         "device_type"=> "/search/search/5/%s"];

  function parseMessage($inventory, $message){

    // match on the format [title](type:id)
    preg_match_all('/\[(.*?)\]\((.*?):(.*?)\)/', $message, $matches);

    // loop through every match and replace the text with the link
    if(count($matches[0]) > 0)
    {
      foreach ($matches[0] as $i => $match) {

        $link = $this->_createLink($matches[1][$i], $matches[2][$i], $matches[3][$i]);

        $message = str_replace($match, $link, $message);
      }
    }
    else
    {
      // if no matches try legacy parsing
      $message = $this->_legacyParseMessage($inventory, $message);
    }

    return $message;
  }

  function _createLink($title, $type, $id){
    return $this->Html->link($title, sprintf($this->link_types[$type], $id));
  }

  function _legacyParseMessage($inventory, $message){

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
