<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

class Computer extends Entity{

  // virtual field "driver_filename"
  protected function _getDriverFilename(){

    // if model exists save the drivers by model, if not save by device name
    if(!empty($this->Model))
    {
      return sprintf("%d.%s.zip",$this->DeviceType, strtolower(str_replace(' ', '_', $this->Model)));
    }
    else
    {
      return sprintf("%d.%s.zip",$this->DeviceType, strtolower(str_replace(' ', '_', $this->ComputerName)));
    }
  }
}
?>
