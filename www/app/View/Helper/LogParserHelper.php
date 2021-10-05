<?php

class LogParserHelper extends AppHelper {

	function parseMessage($inventory, $message){
		App::uses('HtmlHelper', 'View/Helper');

		$htmlHelper = new HtmlHelper($this->_View);

		//try and find a device name in this string
    foreach(array_keys($inventory) as $aKey)
    {
      $pos = strpos($message, $aKey);

      if($pos !== false)
      {
        $message = substr_replace($message, $htmlHelper->link($aKey,'/inventory/moreInfo/' . $inventory[$aKey]), $pos, strlen($aKey));
      }

    }

		return $message;
	}
}
?>
