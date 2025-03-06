<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ScheduleTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('schedules');

    $this->belongsTo('Command')->setForeignKey('command_id');
  }
}
?>
