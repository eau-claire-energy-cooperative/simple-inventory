<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CommandTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('commands');
  }
}
?>
