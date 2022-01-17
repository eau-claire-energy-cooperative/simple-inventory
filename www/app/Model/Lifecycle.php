<?php
   class Lifecycle extends AppModel {

   	var $useTable = 'lifecycles';
    var $belongsTo = array('Applications' => array('foreignKey' => 'application_id'));
}
?>
