<?php
Configure::write('debug',0);

//add the headers
$this->Csv->addRow(array('Name', 'Version', 'Assigned Devices', 'Monitoring'));

//add each application's information to the file
foreach ($applications as $post){

$this->Csv->addRow(array($post['Applications']['name'], $post['Applications']['version'], count($post['Computer']), $post['Applications']['monitoring']));

}

echo $this->Csv->render();

?>
