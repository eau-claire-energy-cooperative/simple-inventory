<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LifecycleTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('lifecycles');

    $this->belongsTo('Application')->setForeignKey('application_id');
  }
}
?>
