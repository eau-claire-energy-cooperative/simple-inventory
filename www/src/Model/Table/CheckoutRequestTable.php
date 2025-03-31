<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CheckoutRequestTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('checkout_request');

    //Note - setting property to "device" as the key name conflicts, easier to change here
    $this->belongsTo('DeviceType')->setForeignKey('device_type')->setProperty('device');

    $this->belongsToMany('Computer', ['through'=>'CheckoutReservation',
                                        'foreignKey'=>'request_id',
                                        'targetForeignKey'=>'device_id']);
  }
}
?>
