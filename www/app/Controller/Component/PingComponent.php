<?php

require ("Net/Ping.php");

class PingComponent extends Component {
	var $name = "Ping Component";

	function ping($host){

		$pingObj = Net_Ping::factory();
		$response = $pingObj->ping($host);

		if(!isset($response->_transmitted))
		{
			//we can't find the host name, assume it's off
			$response->_transmitted = 1;
			$response->_received = 0;
		}

		$result = array('transmitted'=>$response->_transmitted,'received'=>$response->_received);

		return $result;
	}

	function wol($host, $mac)
	{
		//get the broadcast addr
		$range = $this->iprange($host,24);
		$broadcast = $range['last_ip'];

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

	function shutdown_computer($computer,$restart,$settings){

		$exec_string = 'net rpc shutdown -I ' . $computer . ' -U ' . $settings['domain_username'] . '%' . $settings['domain_password'] . ' ';

		if($restart == 'true'){
			$exec_string = $exec_string . '-r ';
		}

		$exec_string = $exec_string . '-C "' . $settings['shutdown_message'] . '"';

		exec($exec_string);
	}

	function iprange($ip,$mask=24,$return_array=FALSE) {
		$corr=(pow(2,32)-1)-(pow(2,32-$mask)-1);
		$first=ip2long($ip) & ($corr);
		$length=pow(2,32-$mask)-1;
		if (!$return_array) {
			return array(
					'first'=>$first,
					'size'=>$length+1,
					'last'=>$first+$length,
					'first_ip'=>long2ip($first),
					'last_ip'=>long2ip($first+$length)
			);
		}
		$ips=array();
		for ($i=0;$i<=$length;$i++) {
			$ips[]=long2ip($first+$i);
		}
		return $ips;
	}

}
?>
