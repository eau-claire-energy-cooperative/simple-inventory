<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class AjaxController extends AppController {

  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('Ping');
    $this->viewBuilder()->setLayout('ajax');
  }

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function checkRunning($id){
	  //get the IP of the device
	  $computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

    $isRunning = $result = ['transmitted'=>1,'received'=>0];
    if($computer)
    {
	     $isRunning = $this->Ping->ping($computer['IPaddress']);
    }

    $this->set('result', $isRunning);
    $this->render('json');
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
			$options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);

			if ($options >=0)
			{
				$e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 9);
				socket_close($sock);
			}
		}

    $this->render('json');
	}
}
?>
