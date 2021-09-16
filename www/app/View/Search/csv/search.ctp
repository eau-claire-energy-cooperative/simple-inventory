<?php
Configure::write('debug',0);
//header values
$headers = array_merge(array('Device Type'), array_values($allAttributes));
$this->Csv->addRow($headers);

foreach ($results as $post){
    $valuesArray = array($post['DeviceType']['name']);

    foreach(array_keys($allAttributes) as $a){

      //some attributes have special handling
      if($a == 'Location')
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

  	$this->Csv->addRow($valuesArray);
}

echo $this->Csv->render();

?>
