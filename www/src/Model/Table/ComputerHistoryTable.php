<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ComputerHistoryTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('computer_history');

    //$this->belongsTo('Computer')->setForeignKey('comp_id');
  }
}
?>
