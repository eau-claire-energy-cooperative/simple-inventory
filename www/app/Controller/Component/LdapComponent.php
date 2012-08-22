<?php
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
    		if(ldap_bind($this->dsPointer,$ldapUser[0]['dn'],$pass))
    		{
    			$result = true;
    		}
    	}

    	$this->disconnect();
    	
    	return $result;
    }
    
    private function getUser($user){
    	
    	if(isset($this->dsPointer))
    	{
    		$attributes = array('dn','givenName','sn','mail','samaccountname','memberof');
    		$query = ldap_search($this->dsPointer,$this->baseDN,"(samaccountname=" . $user . ")",$attributes);
    		
    		if($query)
    		{
    			ldap_sort($this->dsPointer,$query,'sn');
    			
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
    
    private function connect(){
    	$this->dsPointer = ldap_connect($this->host,$this->port);
    	ldap_set_option($this->dsPointer,LDAP_OPT_PROTOCOL_VERSION,3);
    	ldap_bind($this->dsPointer,$this->user,$this->password);
    }
    
    private function disconnect(){
    	if(isset($this->dsPointer))
    	{
    		ldap_close($this->dsPointer);
    	}
    }
}
?>