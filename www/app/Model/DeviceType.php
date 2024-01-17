<?php
   class DeviceType extends AppModel {

   	var $useTable = 'device_types';
    var $virtualFields = array('slug'=>"LOWER(REPLACE(DeviceType.name,' ','_'))");
    var $findMethods = array("checkoutEnabled" => true);

   	var $hasMany = array(
   	    'Computer' => array(
   	        'foreignKey' => 'DeviceType'
   	    )
   	);
}
