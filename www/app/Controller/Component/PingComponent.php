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
	
	function wol($broadcast, $mac)
	{
		$mac_array = explode(':', $mac);
	
		$hwaddr = '';
	
		foreach($mac_array AS $octet)
		{
			$hwaddr .= chr(hexdec($octet));
		}
	
		// Create Magic Packet
	
		$packet = '';
		for ($i = 1; $i <= 6; $i++)
		{
			$packet .= chr(255);
		}
	
		for ($i = 1; $i <= 16; $i++)
		{
			$packet .= $hwaddr;
		}
	
		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if ($sock)
		{
			$options = socket_set_option($sock, 1, 6, true);
	
			if ($options >=0)
			{
				$e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 9);
				socket_close($sock);
			}
		}
	}
	
}
?>