<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

class Application extends Entity{

  // virtual field "full_name
  protected function _getFullName(){

    return sprintf("%s v%s", $this->name, $this->version);
  }
}
?>
