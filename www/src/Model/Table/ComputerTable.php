<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ComputerTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('computer');

    $this->belongsTo('DeviceType')->setForeignKey('DeviceType');
    $this->belongsTo('Location')->setForeignKey('ComputerLocation');

    $this->hasMany('Disk')->setForeignKey('comp_id')->setSort('Disk.label');
    $this->hasMany('ComputerLogin')->setForeignKey('comp_id')->setSort('ComputerLogin.LoginDate desc');

    $this->belongsToMany('Application', ['joinTable'=>'application_installs',
                                          'foreignKey'=>'comp_id',
                                          'targetForeignKey'=>'application_id',
                                          'sort'=>['Application.name', 'Application.version']]);
    $this->belongsToMany('CheckoutRequest', ['through'=>'CheckoutReservation',
                                        'foreignKey'=>'device_id',
                                        'targetForeignKey'=>'request_id',
                                        'order'=>['CheckoutRequest.check_out_date']]);
    $this->belongsToMany('LicenseKey', ['through'=>'ComputerLicense',
                                        'foreignKey'=>'device_id',
                                        'targetForeignKey'=>'license_id']);
  }
}
?>
