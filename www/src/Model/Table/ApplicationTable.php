<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ApplicationTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('applications');

    $this->hasOne('Lifecycle')->setForeignKey('application_id');

    $this->belongsToMany('Computer', ['joinTable'=>'application_installs',
                                     'foreignKey'=>'application_id',
                                     'targetForeignKey'=>'comp_id']);
  }
}
?>
