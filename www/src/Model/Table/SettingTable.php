<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SettingTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('settings');
  }
}
?>
