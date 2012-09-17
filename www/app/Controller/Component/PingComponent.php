<?php

require ("Net/Ping.php");

class PingComponent extends Component {
	var $name = "Ping Component";

	function ping($host){
		
		$pingObj = Net_Ping::factory();
		$response = $pingObj->ping($host);
		
		$result = array('transmitted'=>$response->_transmitted,'received'=>$response->_received);
		
		return $result;
	}
	
}
?>