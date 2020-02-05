<?php

class LogParserHelper extends AppHelper {
		
	function parseMessage($inventory, $message){
		App::uses('HtmlHelper', 'View/Helper');
		
		$htmlHelper = new HtmlHelper($this->_View);
		
		$messageArray = explode(" ", $message);
		
		for($i = 0; $i < count($messageArray); $i ++)
		{
			$aString = trim($messageArray[$i]);
				
			if(array_key_exists($aString, $inventory))
			{
				$messageArray[$i] = $htmlHelper->link($aString,'/inventory/moreInfo/' . $inventory[$aString]);
			}
		}
		
		return implode(" ",$messageArray);
	}
}
?>
	