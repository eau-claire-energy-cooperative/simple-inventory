<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ComputerTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('computer');

    $this->belongsTo('DeviceType')->setForeignKey('DeviceType');
    $this->belongsTo('Location')->setForeignKey('ComputerLocation');
  }
}
?>
