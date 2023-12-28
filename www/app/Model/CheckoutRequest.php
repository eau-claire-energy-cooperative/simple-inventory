<?php
class CheckoutRequest extends AppModel {

	var $useTable = 'checkout_request';
  var $virtualFields = array('check_out_unix'=>"UNIX_TIMESTAMP(CheckoutRequest.check_out_date)",
                             'check_in_unix'=>"UNIX_TIMESTAMP(CheckoutRequest.check_in_date)");

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
