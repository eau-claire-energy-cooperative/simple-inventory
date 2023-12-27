<?php
class CheckoutRequest extends AppModel {

	var $useTable = 'checkout_request';

	var $belongsTo = array("DeviceType" => array("foreignKey" => "device_type"));
  var $hasAndBelongsToMany = array(
    "Computer" => array('className'=>'Computer',
                            'joinTable'=>'checkout_reservation',
                            'foreignKey'=>'request_id',
                            'associationForeignKey'=>'device_id',
                            'unique'=>true)
  );

}

?>
