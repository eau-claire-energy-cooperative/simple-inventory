<?php
   class Applications extends AppModel {

   	var $useTable = 'applications';
    var $virtualFields = array('full_name'=>"CONCAT(Applications.name,' v',Applications.version)");


    var $hasOne = array('Lifecycle'=>array('className'=>'Lifecycle',
                                           'foreignKey'=>'application_id'
    ));

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
