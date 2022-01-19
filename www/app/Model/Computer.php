<?php
   class Computer extends AppModel {

	var $useTable = 'computer';


	var $belongsTo = array('Location' => array('foreignKey' => 'ComputerLocation'),
                         "DeviceType" => array("foreignKey" => "DeviceType") );
	var $hasMany = array('Disk'=>array('foreignKey'=>"comp_id", 'order'=>'Disk.label'),
	    'ComputerLogin'=>array('foreignKey'=>'comp_id','order'=>'ComputerLogin.LoginDate desc'),
	    'License'=>array('foreignKey'=>'comp_id', 'order'=>'License.ProgramName asc'));

  var $hasAndBelongsToMany = array(
    "Applications" => array('className'=>'Applications',
                            'joinTable'=>'application_installs',
                            'foreignKey'=>'comp_id',
                            'associationForeignKey'=>'application_id',
                            'unique'=>'keepExisting',
                            'order'=>array('Applications.name', 'Applications.version'))
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
