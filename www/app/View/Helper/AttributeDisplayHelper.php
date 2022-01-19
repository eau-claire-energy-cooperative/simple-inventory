<?php

class AttributeDisplayHelper extends AppHelper {
	var $helpers = array('Html','DiskSpace','Time');

	function drawTable($tableRows, $validAttributes, $computer){
	    $result =  '<div class="row">';
	    $result = $result . '<table class="table table-striped">';

	    foreach($tableRows as $aTable)
	    {
	        $result = $result . "<tr>";
	        foreach($aTable as $attribute)
	        {
	            $result = $result . '<th style="width: 250px;">' . $validAttributes[$attribute] . '</th>';
	        }

	        $tableCount = count($aTable);
	        while($tableCount < 5)
	        {
	            $result = $result . '<th style="width: 250px;"></th>';
	            $tableCount ++;
	        }

	        $result = $result . "</tr>";
	        $result = $result . "<tr>";

	        foreach($aTable as $attribute)
	        {
	            $result = $result . '<td>' . $this->displayAttribute($attribute,$computer) . '</td>';
	        }

	        $tableCount = count($aTable);
	        while($tableCount < 5)
	        {
	            $result = $result . "<td></td>";
                $tableCount ++;
	        }

	        $result = $result . "</tr>";
	    }

	    $result = $result . '</table>';
	    $result = $result . '</div>';

	    return $result;
	}

	function displayAttribute($attribute,$computer,$edit=false){
		$result = '';

		if($attribute == 'ComputerName')
		{
			$result = $computer['Computer']['ComputerName'];
		}
		else if($attribute == 'ComputerLocation')
		{
			$result = $this->Html->link( $computer['Location']['location'], array('controller'=>'search','action' => 'search', 0, $computer['Computer']['ComputerLocation']));
		}
		else if($attribute == 'CurrentUser')
		{
			$result = $this->Html->link($computer['Computer']['CurrentUser'],array('controller'=>'inventory','action'=>'loginHistory',$computer['Computer']['id']));
		}
		else if ($attribute == 'SerialNumber')
		{
			$result = $computer['Computer']['SerialNumber'];
		}
		else if ($attribute == 'AssetId')
		{
			$result = $computer['Computer']['AssetId'];
		}
		else if($attribute == 'ApplicationUpdates')
		{
			$result = $computer['Computer']['ApplicationUpdates'];
		}
    else if($attribute == 'Manufacturer')
    {
      $result = $computer['Computer']['Manufacturer'];
    }
		else if ($attribute == 'Model')
		{
			$result = $this->Html->link($computer['Computer']['Model'], array('controller'=>'search','action' => 'search', 1, $computer['Computer']['Model']));
		}
		else if ($attribute == 'OS')
		{
			$result = $this->Html->link( $computer['Computer']['OS'], array('controller'=>'search','action' => 'search', 2, $computer['Computer']['OS']));
		}
		else if ($attribute == 'CPU')
		{
			$result = $computer['Computer']['CPU'];
		}
		else if ($attribute == 'Memory')
		{
			$result = $this->Html->link($computer['Computer']['Memory'] . " GB", array('controller'=>'search','action' => 'search', 3, $computer['Computer']['Memory']));
           	$result = $result . ' (' . $this->DiskSpace->compare($computer['Computer']['Memory'],$computer['Computer']['MemoryFree']) . "% free)";
		}
		else if ($attribute == 'NumberOfMonitors')
		{
			$result = $this->Html->link( $computer['Computer']['NumberOfMonitors'], array('controller'=>'search','action' => 'search', 4, $computer['Computer']['NumberOfMonitors']));
		}
		else if ($attribute == 'IPaddress')
		{
			$result = $computer['Computer']['IPaddress'];
		}
		else if ($attribute == 'IPv6address')
		{
		    $result = $computer['Computer']['IPv6address'];
		}
		else if ($attribute == 'MACaddress')
		{
			$result = $computer['Computer']['MACaddress'];
		}
		else if ($attribute == 'DriveSpace')
		{
			foreach($computer['Disk'] as $aDisk){
				if($aDisk['type'] == 'Local')
				{
					$result = $result . $aDisk['label'] . " - " . $this->DiskSpace->toString($aDisk['total_space']) . '(' . $this->DiskSpace->compare($aDisk['total_space'],$aDisk['space_free']). '% free)';
				}
				else
				{
					$result = $result . $aDisk['label'] . " - " . $aDisk['type'];
				}

        if($edit){
          $result = $result . '<a href="' . $this->Html->url('/inventory/delete_disk/' . $aDisk['id'] . '/' . $computer['Computer']['id']) . '" class="ml-1 text-danger" title="Delete Disk"><i class="mdi mdi-delete icon-inline icon-sm"></i></a>';
        }

        $result = $result . "<br />";
			}
		}
		else if ($attribute == 'LastUpdated')
		{
			$result = $this->Time->niceShort($computer['Computer']['LastUpdated']);
		}
    else if($attribute == 'SupplicantUsername'){
      $result = $computer['Computer']['SupplicantUsername'];
    }
    else if($attribute == 'SupplicantPassword')
    {
      if(strlen($computer['Computer']['SupplicantPassword']) > 0)
      {
        $result = '<span id="supplicant_password">*************</span> ' .
        '<a href="" onClick="return showOriginal(\'supplicant_password\',\'' . $computer['Computer']['SupplicantPassword'] . '\')" class="h6 text-decoration-none"><i class="material-icons mi-sm mi-inline">visibility</i></a>'; // don't show this in the main UI
      }
      else {
        $result = "";
      }
    }

		return $result;
	}
}

?>
