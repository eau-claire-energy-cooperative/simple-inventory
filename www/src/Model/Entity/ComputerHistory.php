<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ComputerHistory extends Entity{

  // virtual fields
  protected function _getOrigAsJson(){
    $result = json_decode($this->orig_json, true);
    ksort($result);

    return $result;
  }

  protected function _getUpdatedAsJson(){
    $result = json_decode($this->updated_json, true);
    ksort($result);

    return $result;
  }
}
?>
