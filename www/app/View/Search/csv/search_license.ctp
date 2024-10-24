<?php

Configure::write('debug',0 );

//header values
$headers = array('Device Name', 'License Key', 'Location', 'Last Updated');
$this->Csv->addRow($headers);


foreach ($results as $license){
  foreach($license['Computer'] as $computer){
    $valuesArray = array();
    // keycode can be in one of two places
    if(isset($license['LicenseKey'])){
      $keycode = $license['LicenseKey']['Keycode'];
    }
    else {
      $keycode = $license['Keycode'];
    }
    if(isset($computer['ComputerName'])){
      $valuesArray[] = $computer['ComputerName'];
      $valuesArray[] = $keycode;
      $valuesArray[] = $locations[$computer['ComputerLocation']];
      $valuesArray[] = $computer['LastUpdated'];

      $this->Csv->addRow($valuesArray);
    }
  }
}
echo $this->Csv->render();

?>
