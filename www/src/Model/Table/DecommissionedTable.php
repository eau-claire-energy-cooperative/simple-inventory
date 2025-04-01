<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class DecommissionedTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('decommissioned');

    $this->belongsTo('Location')->setForeignKey('ComputerLocation');
  }
}
?>
