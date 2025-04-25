<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LicenseTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('licenses');

    $this->hasMany('LicenseKey')->setForeignKey('license_id')->setSort('LicenseKey.Keycode');
  }
}
?>
