<?php
   class Computer extends AppModel {
   	
	var $useTable = 'computer';

	
	var $belongsTo = array('Location' => array('foreignKey' => 'ComputerLocation') ); 
	var $hasMany = array('Disk'=>array('foreignKey'=>"comp_id"));
	
	
	
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