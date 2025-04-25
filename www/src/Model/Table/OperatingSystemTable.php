<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class OperatingSystemTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('operating_systems');
  }
}
?>
