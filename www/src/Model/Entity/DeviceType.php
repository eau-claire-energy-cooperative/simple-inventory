<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

class DeviceType extends Entity{

  // virtual field "slug"
  protected function _getSlug(){

    return strtolower(str_replace(' ', '_', $this->name));
  }
}
?>
