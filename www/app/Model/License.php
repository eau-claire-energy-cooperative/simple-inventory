<?php
   class License extends AppModel {
   	
	var $useTable = 'licenses';
	var $belongsTo = array('Computer' => array('foreignKey' => 'comp_id') ); 
}
  
?>