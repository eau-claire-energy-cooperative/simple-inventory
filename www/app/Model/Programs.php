<?php
   class Programs extends AppModel {
   			
   	var $useTable = 'programs';
	var $belongsTo = array('Computer' => array('foreignKey' => 'comp_id'));
	
	
   	

   		
   	
   }
   
