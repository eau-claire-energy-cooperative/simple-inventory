<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class DeviceTypeTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('device_types');

    $this->hasMany('Computer')->setForeignKey('DeviceType');
    $this->hasMany('ComputerCheckout', ['className'=>'Computer'])->setForeignKey('DeviceType')->setConditions(['ComputerCheckout.CanCheckout'=>'true']);
  }
}
?>
