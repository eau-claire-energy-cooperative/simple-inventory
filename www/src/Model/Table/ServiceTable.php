<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ServiceTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('services');

    $this->belongsTo('Computer')->setForeignKey('comp_id');
  }
}
?>
