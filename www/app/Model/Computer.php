<?php
   class Computer extends AppModel {

	var $useTable = 'computer';


	var $belongsTo = array('Location' => array('foreignKey' => 'ComputerLocation'),
                         "DeviceType" => array("foreignKey" => "DeviceType") );
	var $hasMany = array('Disk'=>array('foreignKey'=>"comp_id"),
	    'ComputerLogin'=>array('foreignKey'=>'comp_id','order'=>'ComputerLogin.LoginDate desc'),
	    'License'=>array('foreignKey'=>'comp_id', 'order'=>'License.ProgramName asc'));



	  public $validate = array(
        'ComputerName' => array(
            'rule' => 'notEmpty'
        ),
        'SerialNumber' => array(
            'rule' => 'notEmpty'
        ),
		'AssetId' => array(
            'rule' => 'notEmpty'
        ),
		'Location' => array(
            'rule' => 'notEmpty'
        ),
		'Model' => array(
            'rule' => 'notEmpty'
             ),
		'OS' => array(
            'rule' => 'notEmpty'
             ),
		'ComputerLocation' => array(
            'rule' => 'notEmpty'
            ));
}

?>
