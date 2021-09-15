<?php
Configure::write('debug',0);
//header values
$headers = array('Computer Name','Current User','Location','Serial Number','Asset ID','Manufacturer','Model','Operating System','CPU','Memory','Number of Monitors','IP Address','MAC Address','C: Drive Space','Last Updated');
$this->Csv->addRow($headers);

foreach ($results as $post){
    $valuesArray = array();

    $valuesArray[] = $post['Computer']['ComputerName'];
    $valuesArray[] = $post['Computer']['CurrentUser'];
    $valuesArray[] = $locations[$post['Computer']['ComputerLocation']];
    $valuesArray[] = $post['Computer']['SerialNumber'];
	$valuesArray[] = $post['Computer']['AssetId'];
  $valuesArray[] = $post['Computer']['Manufacturer'];
	$valuesArray[] = $post['Computer']['Model'];
	$valuesArray[] = $post['Computer']['OS'];
	$valuesArray[] = $post['Computer']['CPU'];
	$valuesArray[] = $post['Computer']['Memory'];
	$valuesArray[] = $post['Computer']['NumberOfMonitors'];
	$valuesArray[] = $post['Computer']['IPaddress'];
	$valuesArray[] = $post['Computer']['MACaddress'];
	$valuesArray[] = $this->DiskSpace->toString($post['Computer']['DiskSpace']);
	$valuesArray[] = $this->Time->niceShort($post['Computer']['LastUpdated']);

  	$this->Csv->addRow($valuesArray);
}

echo $this->Csv->render();

?>
