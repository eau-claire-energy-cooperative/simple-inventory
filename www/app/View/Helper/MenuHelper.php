<?php

class MenuHelper extends AppHelper {
		
	function getProfileImage($gravatar_address){
		App::uses('HtmlHelper', 'View/Helper');
		$result = '/img/profile/user-profile-smile.svg';
		
		if($gravatar_address != null && trim($gravatar_address) != "")
		{
		  $result = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($gravatar_address))) . "?d=mp";
		}
		
		return $result;
	}
	
	function getActiveMenu($name, $active){
	    $result = '';

	    if(trim($active) == '')
	    {
	        $active = $this->request->params['controller'];
	    }
	    
	    if(trim($name) == trim($active))
	    {
	        $result = 'active';
	    }
	    
	    return $result;
	}
}
?>