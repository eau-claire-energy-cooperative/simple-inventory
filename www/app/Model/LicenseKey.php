<?php
   class LicenseKey extends AppModel {

	var $useTable = 'license_keys';
	var $belongsTo = array('Computer' => array('foreignKey' => 'comp_id') );
}

?>
