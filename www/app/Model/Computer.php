<?php
   class Computer extends AppModel {

	var $useTable = 'computer';


	var $belongsTo = array('Location' => array('foreignKey' => 'ComputerLocation'),
                         "DeviceType" => array("foreignKey" => "DeviceType") );
	var $hasMany = array('Disk'=>array('foreignKey'=>"comp_id", 'order'=>'Disk.label'),
	    'ComputerLogin'=>array('foreignKey'=>'comp_id', 'limit'=>50, 'order'=>'ComputerLogin.LoginDate desc'));

  var $hasAndBelongsToMany = array(
    "Applications" => array('className'=>'Applications',
                            'joinTable'=>'application_installs',
                            'foreignKey'=>'comp_id',
                            'associationForeignKey'=>'application_id',
                            'unique'=>'keepExisting',
                            'order'=>array('Applications.name', 'Applications.version')),
    "CheckoutRequest" => array('className'=>'CheckoutRequest',
                            'joinTable'=>'checkout_reservation',
                            'foreignKey'=>'device_id',
                            'associationForeignKey'=>'request_id',
                            'unique'=>true,
                            'order'=>array('CheckoutRequest.check_out_date')),
    "LicenseKey" => array('className'=>'LicenseKey',
                          'joinTable'=>'computer_license',
                          'foreignKey'=>'device_id',
                          'associationForeignKey'=>'license_id',
                          'unique'=>true)
  );



	public $validate = array(
        'ComputerName' => array(
            'rule' => 'notBlank'
        ),
        'DeviceType' => array(
            'rule' => 'notBlank'
        ),
  		'ComputerLocation' => array(
              'rule' => 'notBlank'
    ));
}

?>
