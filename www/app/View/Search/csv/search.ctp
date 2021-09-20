<?php
Configure::write('debug',0);

// get the attributes that are common to all devices (exist in each least one device)
$commonAttributes = find_attribute_list($results);

//header values
$headers = array_merge(array('Device Type'), array_values($commonAttributes));
$this->Csv->addRow($headers);

foreach ($results as $post){
    $valuesArray = array($post['DeviceType']['name']);

    foreach(array_keys($allAttributes) as $a){

      if(in_array($a, $commonAttributes))
      {
        //some attributes have special handling
        if($a == 'ComputerLocation')
        {
          $valuesArray[] = $locations[$post['Computer']['ComputerLocation']];
        }
        else if($a == 'DriveSpace')
        {
          //do nothing for drivespace as there are multiple entries here
        }
        else if($a == 'LastUpdated')
        {
          $valuesArray[] = $this->Time->niceShort($post['Computer']['LastUpdated']);
        }
        else
        {
          $valuesArray[] = $post['Computer'][$a];
        }
      }
    }

  	$this->Csv->addRow($valuesArray);
}

echo $this->Csv->render();

function find_attribute_list($devices, $requiredAttributes = array()){
  $results = $requiredAttributes;

  foreach($devices as $d)
  {
    $attributes = explode(',', $d['DeviceType']['attributes']);

    $results = array_unique(array_merge($results, $attributes));
  }

  return $results;
}

?>
