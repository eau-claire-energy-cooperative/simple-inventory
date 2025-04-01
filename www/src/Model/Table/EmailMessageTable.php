<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class EmailMessageTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('email_queue');

  }
}
?>
