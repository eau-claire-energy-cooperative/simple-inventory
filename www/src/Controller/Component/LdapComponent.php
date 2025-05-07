<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class LdapComponent extends Component {
    var $name = "Ldap Component";

    //ldap specific variables
    var $host = null;
    var $port = 389;
    var $baseDN = null;
  	var $user = null;
  	var $password = null;

  	var $dsPointer = null;

    public function setup($settings){
    	//setup the ldap variables
    	$this->host = $settings['host'];
    	$this->port = $settings['port'];
    	$this->baseDN = $settings['baseDN'];
    	$this->user = $settings['user'];
    	$this->password = $settings['password'];
    }

    public function auth($user,$pass)
    {	$result = false;

    	$this->connect();
    	$ldapUser = $this->getUser($user);

    	if(isset($ldapUser) && count($ldapUser) > 0)
    	{
        // ldap_bind will always succeed with blank password
        if(empty($pass)){
          $pass = "BLANKPASSWORD";
        }

    		if(@ldap_bind($this->dsPointer,$ldapUser[0]['dn'],$pass))
    		{
    			$result = true;
    		}
    	}
    	$this->disconnect();

    	return $result;
    }

	public function getComputers(){
		$result = null;

		$this->connect();

		if(isset($this->dsPointer))
		{
			$query = ldap_search($this->dsPointer,$this->baseDN,"(objectClass=Computer)");

			if($query){
				//ldap_sort($this->dsPointer,$query,'cn');

				$result = ldap_get_entries($this->dsPointer,$query);
			}
		}

		$this->disconnect();

		return $result;
	}

  public function getComputerLocation($name){
    $result = null;

    $this->connect();

    $query = ldap_search($this->dsPointer, $this->baseDN, sprintf("(&(objectClass=Computer)(cn=%s))", $name));

    if($query)
    {
      $device = ldap_get_entries($this->dsPointer, $query);

      // check the count and if a location exists
      if($device != null && $device['count'] > 0 && isset($device[0]['location']))
      {
        $result = $device[0]['location'][0];
      }
    }

    $this->disconnect();

    return $result;
  }

  public function connect(){
    $this->dsPointer = ldap_connect($this->host,$this->port);
    ldap_set_option($this->dsPointer,LDAP_OPT_PROTOCOL_VERSION,3);
    return ldap_bind($this->dsPointer,$this->user,$this->password);
  }

  public function disconnect(){
    if(isset($this->dsPointer))
    {
      ldap_close($this->dsPointer);
    }
  }

  private function getUser($user){

  	if(isset($this->dsPointer))
  	{
  		$attributes = array('dn','givenName','sn','mail','samaccountname','memberof');
  		$query = ldap_search($this->dsPointer,$this->baseDN,"(samaccountname=" . $user . ")",$attributes);

  		if($query)
  		{
  			//ldap_sort($this->dsPointer,$query,'sn');

  			$result =  ldap_get_entries($this->dsPointer, $query);

  			return $result;
  		}
  		else
  		{
  			return null;
  		}
  	}
  	else
  	{
  		return null;
  	}
  }
}
?>
