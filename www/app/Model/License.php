<?php
   class License extends AppModel {

	var $useTable = 'license_keys';
	var $belongsTo = array('Computer' => array('foreignKey' => 'comp_id') );
}

?>
