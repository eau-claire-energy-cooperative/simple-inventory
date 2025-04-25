<?php
namespace App\View\Helper;

use Cake\View\Helper;

class AttributeDisplayHelper extends Helper
{
  public $helpers = ['Html', 'DiskSpace', 'Time', 'Url'];

  function drawTable($tableRows, $validAttributes, $computer, $createLinks=true){
    $result =  '<div class="row">';
    $result = $result . '<table class="table table-striped">';

    foreach($tableRows as $aTable)
    {
        $result = $result . "<tr>";
        $totalAttributes = 0;
        foreach($aTable as $attribute)
        {
          //check that attribute exists in entity
          if(isset($computer[$attribute]) || ($attribute == 'DriveSpace' && isset($computer['disk'])))
          {
            $result = $result . '<th style="width: 250px;">' . $validAttributes[$attribute] . '</th>';
            $totalAttributes ++;
          }
        }

        // pad rest of table
        $tableCount = $totalAttributes;
        while($tableCount < 5)
        {
            $result = $result . '<th style="width: 250px;"></th>';
            $tableCount ++;
        }

        $result = $result . "</tr>";
        $result = $result . "<tr>";

        foreach($aTable as $attribute)
        {
          // again, make sure attribute exists in entity
          if(isset($computer[$attribute])  || ($attribute == 'DriveSpace' && isset($computer['disk'])))
          {
            $result = $result . '<td>' . $this->displayAttribute($attribute,$computer,false,$createLinks) . '</td>';
          }
        }

        $tableCount = $totalAttributes;
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

  function displayAttribute($attribute,$computer,$edit=false,$createLinks=true){
  	$result = '';

  	if($attribute == 'ComputerName')
  	{
  		$result = $computer['ComputerName'];
  	}
  	else if($attribute == 'ComputerLocation')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link( $computer['location']['location'], ['controller'=>'search','action' => 'search', 0, $computer['ComputerLocation']]);
      }
      else
      {
        $result = $computer['location']['location'];
      }
  	}
  	else if($attribute == 'CurrentUser')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link($computer['CurrentUser'], ['controller'=>'inventory','action'=>'loginHistory',$computer['id']]);
      }
      else
      {
        $result = $computer['CurrentUser'];
      }
  	}
  	else if ($attribute == 'SerialNumber')
  	{
  		$result = $computer['SerialNumber'];
  	}
  	else if ($attribute == 'AssetId')
  	{
  		$result = $computer['AssetId'];
  	}
  	else if($attribute == 'ApplicationUpdates')
  	{
  		$result = $computer['ApplicationUpdates'];
  	}
    else if($attribute == 'Manufacturer')
    {
      $result = $computer['Manufacturer'];
    }
  	else if ($attribute == 'Model')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link($computer['Model'], ['controller'=>'search','action' => 'search', 1, $computer['Model']]);
      }
      else
      {
        $result = $computer['Model'];
      }
  	}
  	else if ($attribute == 'OS')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link( $computer['OS'], ['controller'=>'search','action' => 'search', 2, $computer['OS']]);
      }
      else
      {
        $result = $computer['OS'];
      }
  	}
  	else if ($attribute == 'CPU')
  	{
  		$result = $computer['CPU'];
  	}
  	else if ($attribute == 'Memory')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link($computer['Memory'] . " GB",  ['controller'=>'search','action' => 'search', 3, $computer['Memory']]);
      }
      else
      {
        $result = $computer['Memory'] . " GB";
      }

      $result = $result . ' (' . $this->DiskSpace->compare($computer['Memory'],$computer['MemoryFree']) . "% free)";
  	}
  	else if ($attribute == 'NumberOfMonitors')
  	{
      if($createLinks)
      {
  		  $result = $this->Html->link( $computer['NumberOfMonitors'], ['controller'=>'search','action' => 'search', 4, $computer['NumberOfMonitors']]);
      }
      else
      {
        $result = $computer['NumberOfMonitors'];
      }
  	}
  	else if ($attribute == 'IPaddress')
  	{
  		$result = $computer['IPaddress'];
  	}
  	else if ($attribute == 'IPv6address')
  	{
  	    $result = $computer['IPv6address'];
  	}
  	else if ($attribute == 'MACaddress')
  	{
  		$result = $computer['MACaddress'];
  	}
  	else if ($attribute == 'DriveSpace')
  	{
  		foreach($computer['disk'] as $aDisk){
  			if($aDisk['type'] == 'Local')
  			{
  				$result = $result . $aDisk['label'] . " - " . $this->DiskSpace->toString($aDisk['total_space']) . '(' . $this->DiskSpace->compare($aDisk['total_space'],$aDisk['space_free']). '% free)';
  			}
  			else
  			{
  				$result = $result . $aDisk['label'] . " - " . $aDisk['type'];
  			}

        if($edit){
          $result = $result . '<a href="' . $this->Url->build('/inventory/delete_disk/' . $aDisk['id'] . '/' . $computer['id']) . '" class="ml-1 text-danger" title="Delete Disk"><i class="mdi mdi-delete icon-inline icon-sm"></i></a>';
        }

        $result = $result . "<br />";
  		}
  	}
  	else if ($attribute == 'LastUpdated')
  	{
  		$result = $this->Time->nice($computer['LastUpdated']);
  	}
    else if($attribute == 'SupplicantUsername'){
      $result = $computer['SupplicantUsername'];
    }
    else if($attribute == 'SupplicantPassword')
    {
      if(strlen($computer['SupplicantPassword']) > 0)
      {
        $result = '<span id="supplicant_password">*************</span> ' .
        '<a href="" onClick="return showOriginal(\'supplicant_password\',\'' . $computer['SupplicantPassword'] . '\')" class="h6 text-decoration-none"><i class="mdi mdi-eye mdi-inline icon-sm"></i></a>'; // don't show this in the main UI
      }
      else {
        $result = "";
      }
    }

  	return $result;
  }
}
?>
