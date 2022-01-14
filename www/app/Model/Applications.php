<?php
   class Applications extends AppModel {

   	var $useTable = 'applications';
    var $virtualFields = array('full_name'=>"CONCAT(Applications.name,' ',Applications.version)");

    var $hasAndBelongsToMany = array(
      "Computer" => array('className'=>"Computer",
                              'joinTable'=>'application_installs',
                              'foreignKey'=>'application_id',
                              'associationForeignKey'=>'comp_id',
                              'unique'=>'keepExisting')
    );

    public $validate = array(
      "name" => array(
        "rule" => array('isUnique', array('name', 'version'), false),
        "message" => 'This application name and version already exist'
      )
    );
}
?>
