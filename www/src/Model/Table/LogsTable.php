<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LogsTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('logs');
  }
}
?>
