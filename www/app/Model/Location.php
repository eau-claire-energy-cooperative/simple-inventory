<?php
   class Location extends AppModel {
   			
   	var $useTable = 'location';
   	
   	var $hasMany = array(
   	    'Computer' => array(
   	        'foreignKey' => 'ComputerLocation'
   	    )
   	);

}
   
