<?php
   class Setting extends AppModel {
   			
   	var $useTable = 'settings';
   	
	//encrypt values before saving
	public function beforeSave($options = array()){
		$this->data['Setting']['value'] = Security::rijndael($this->data['Setting']['value'], Configure::read('Settings.encryption_key'),'encrypt');
		
		return true;
	}
	
	//decrypt values after loading
	public function afterFind($results, $primary = false){

		for($i = 0; $i < count($results); $i ++)
		{
			if(isset($results[$i]['Setting']['value']))
			{
				$aValue = $results[$i]['Setting']['value'];
				$results[$i]['Setting']['value'] = Security::rijndael($aValue, Configure::read('Settings.encryption_key'),'decrypt');
			}
		}
		
		return $results;
	}
	
	
   }
?>