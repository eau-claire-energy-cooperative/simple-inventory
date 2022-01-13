<?php
   class Applications extends AppModel {

   	var $useTable = 'applications';

    var $hasAndBelongsToMany = array(
      "Computer" => array('className'=>"Computer",
                              'joinTable'=>'application_installs',
                              'foreignKey'=>'application_id',
                              'associationForeignKey'=>'comp_id',
                              'unique'=>'keepExisting')
    );
}
?>
