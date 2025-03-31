<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CheckoutReservationTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('checkout_reservation');

    $this->belongsTo('Computer')->setForeignKey('device_id');
    $this->belongsTo('CheckoutRequest')->setForeignKey('request_id');
  }
}
?>
