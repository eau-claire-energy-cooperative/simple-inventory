<?php
   class License extends AppModel {

	var $useTable = 'licenses';

  var $hasMany = array('LicenseKey'=>array('foreignKey'=>"license_id", 'order'=>'LicenseKey.Keycode'));
}

?>
