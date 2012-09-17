<?php
	
class AjaxController extends AppController {
    var $components = array('Session','Ping');
    var $helpers = array('Js');
	var $layout = '';
	var $uses = array('Computer');


	function checkRunning($name){
		$isRunning = $this->Ping->ping($name);
		$this->set('result',$isRunning);
	}
}
?>