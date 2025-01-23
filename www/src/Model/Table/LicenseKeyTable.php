<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LicenseKeyTable extends Table {

  public function initialize(array $config): void
  {
    $this->setTable('license_keys');

    $this->belongsTo('License')->setForeignKey('license_id');

    $this->belongsToMany('Computer', ['through'=>'ComputerLicense',
                                      'targetForeignKey'=>'license_id']);
  }
}
?>
