<?php
   class LicenseKey extends AppModel {

	var $useTable = 'license_keys';
	var $belongsTo = array('License' => array('foreignKey' => 'license_id') );
  var $hasAndBelongsToMany = array(
    "Computer" => array('className'=>'Computer',
                            'joinTable'=>'computer_license',
                            'foreignKey'=>'license_id',
                            'associationForeignKey'=>'device_id',
                            'unique'=>true)
  );
}

?>
