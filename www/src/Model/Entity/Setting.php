<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Utility\Security;

class Setting extends Entity{

  public function __construct(array $properties = [], array $options = []){
    parent::__construct($properties, $options);

    // decrypt the value, if encrypted
    if(Configure::read('Settings.encrypt') && !empty($this->_fields['value']))
    {
      $this->_fields['value'] = Security::decrypt($this->_fields['value'], Configure::read('Settings.encrypt_key'));
    }
  }
}
?>
