<?php
namespace App\View\Helper;

use Cake\View\Helper;

class MenuHelper extends Helper
{
  function getProfileImage($gravatar_address){

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
        $active = $this->getView()->getRequest()->getParam('controller');
    }

    if(trim($name) == trim($active))
    {
        $result = 'active';
    }

    return $result;
  }
}
?>
