<?php
   class Schedule extends AppModel {
   	
	var $useTable = 'schedules';

	var $belongsTo = array('Command' => array('foreignKey' => 'command_id') );
}
  
?>