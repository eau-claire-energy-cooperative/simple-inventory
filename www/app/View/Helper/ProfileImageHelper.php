<?php

class ProfileImageHelper extends AppHelper {
		
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
}
?>