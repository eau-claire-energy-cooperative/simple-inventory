<?php
   class Setting extends AppModel {

   	var $useTable = 'settings';

	//encrypt values before saving
	public function beforeSave($options = array()){

	    if(Configure::read('Settings.encrypt'))
	    {
            $this->data['Setting']['value'] = Security::encrypt($this->data['Setting']['value'], Configure::read('Settings.encryption_key'));
	    }

		return true;
	}

	//decrypt values after loading
	public function afterFind($results, $primary = false){

	    if(Configure::read('Settings.encrypt'))
	    {
    		for($i = 0; $i < count($results); $i ++)
    		{
    			if(isset($results[$i]['Setting']['value']))
    			{
    				$aValue = $results[$i]['Setting']['value'];
            if(!empty($aValue))
            {
    				      $results[$i]['Setting']['value'] = Security::decrypt($aValue, Configure::read('Settings.encryption_key'));
            }
    			}
    		}
	    }

		return $results;
	}


   }
?>
