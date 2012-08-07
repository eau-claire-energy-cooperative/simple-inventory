<?php
   class Decommissioned extends AppModel {
   			
   	var $useTable = 'decommissioned';

	var $belongsTo = array('Location' => array('foreignKey' => 'Location') ); 
	
   	

   		
   	
   }
   
