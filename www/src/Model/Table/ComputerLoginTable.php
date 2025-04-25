<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ComputerLoginTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('computer_logins');
  }
}
?>
