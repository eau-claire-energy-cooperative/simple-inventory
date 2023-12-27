<?php
   class CheckoutDeviceType extends AppModel {

   	var $useTable = 'device_types';
    var $virtualFields = array('slug'=>"LOWER(REPLACE(CheckoutDeviceType.name,' ','_'))");

   	var $hasMany = array(
   	    'Computer' => array(
   	        'foreignKey' => 'DeviceType',
            'conditions' => array('CanCheckout'=>'true')
   	    )
   	);

}
