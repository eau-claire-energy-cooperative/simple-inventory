<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class PingComponent extends Component {

  function ping($host){
    $result = ['transmitted'=>1, 'received'=>0];

    // host, ttl, timeout
    $pingObj = new \JJG\Ping($host, 128, 3);
    $response = $pingObj->ping();

		if($response !== false)
		{
			//we can't find the host name, assume it's off
			$result['received'] = 1;
		}

		return $result;
	}
}
?>
