<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class DiskTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('disk');
  }
}
?>
