<?php
Configure::write('debug',0);

//add the headers
$this->Csv->addRow(array('Application Name', 'Version', 'Update Needed', 'Last Check Date', 'Next Check Date'));

//add each application's information to the file
foreach ($lifecycles as $post){

$values = array($post['Applications']['name'], $post['Applications']['version']);

//check if update needed
$isDue = ($this->Lifecycle->isDue(date('Y-m-d', strtotime($post['Lifecycle']['last_check'])), $post['Lifecycle']['update_frequency'])) ? "Yes" : "No";

$values[] = $isDue;
$values[] = $this->Time->format(date('Y-m-d', strtotime($post['Lifecycle']['last_check'])), "%m/%d/%Y");
$values[] = $this->Time->format($this->Lifecycle->getNextDate(date('Y-m-d', strtotime($post['Lifecycle']['last_check'])), $post['Lifecycle']['update_frequency']), '%m/%d/%Y');

$this->Csv->addRow($values);

}

echo $this->Csv->render();

?>
