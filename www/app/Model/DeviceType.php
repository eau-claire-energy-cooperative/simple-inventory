<?php
   class DeviceType extends AppModel {

   	var $useTable = 'device_types';

   	var $hasMany = array(
   	    'Computer' => array(
   	        'foreignKey' => 'DeviceType'
   	    )
   	);

}
