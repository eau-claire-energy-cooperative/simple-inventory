<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ComputerHistory extends Entity{

  protected function _getOrigJson($json){
    return json_decode($json, true);
  }
}
?>
