<?php

class MenuHelper extends AppHelper {
		
	function getImage($username){
		App::uses('HtmlHelper', 'View/Helper');
		$result = '/img/profile/';
		
		//massage the name a bit
		$username = strtolower($username);
		$username = str_replace(' ', '_',$username);
		
		if(file_exists(WWW_ROOT . '/img/profile/' . $username . '.jpg'))
		{
		    $result = $result . $username . '.jpg';
		}
		else
		{
		    $result = $result . 'user-profile-smile.svg';
		}
		
		return $result;
	}
	
	function getActiveMenu($name, $active){
	    $result = '';

	    if(trim($active) == '')
	    {
	        $active = $this->request->params['controller'];
	    }
	    
	    if($name == $active)
	    {
	        $result = 'active';
	    }
	    
	    return $result;
	}
}
?>