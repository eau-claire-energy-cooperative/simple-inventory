<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ComputerHistory extends Entity{

  // virtual fields
  protected function _getOrigAsJson(){
    return json_decode($this->orig_json, true);
  }

  protected function _getUpdatedAsJson(){
  }
    return json_decode($this->updated_json, true);
}
?>
