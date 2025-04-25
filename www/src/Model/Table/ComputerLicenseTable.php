<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ComputerLicenseTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('computer_license');

    $this->belongsTo('Computer')->setForeignKey('device_id');
    $this->belongsTo('LicenseKey')->setForeignKey('license_id');
  }
}
?>
