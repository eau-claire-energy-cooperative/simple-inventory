<?php
   class Service extends AppModel {
   			
   	var $useTable = 'services';
	var $belongsTo = array('Computer' => array('foreignKey' => 'comp_id'));
   }
?>