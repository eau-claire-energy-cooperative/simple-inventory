<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LocationTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('location');

    $this->hasMany('Computer')->setForeignKey('ComputerLocation');
  }
}
?>
