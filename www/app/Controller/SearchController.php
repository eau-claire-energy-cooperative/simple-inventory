<?php 

class SearchController extends AppController {
	var $uses = array("Programs","Computer","Location");
	
	var $search_types = array(array("name"=>"Location","field"=>"Computer.ComputerLocation"),
						array('name'=>'Model','field'=>'Computer.Model'),
						array('name'=>'OS','field'=>'Computer.OS'),
						array('name'=>'Memory','field'=>'Computer.Memory'),
						array('name'=>'Monitors','field'=>'Computer.NumberOfMonitors'));
	
	public function beforeFilter(){
		//check if we are using a login method
		if(!$this->Session->check('authenticated')){
			//check if we are using a login method
			$loginMethod = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'auth_type')));
	
			if(isset($loginMethod) && trim($loginMethod['Setting']['value']) == 'none')
			{
				//we aren't authenticating, just keep moving
				$this->Session->write('authenticated','true');
			}
			//check, we may already be trying to go to the login page
			else if($this->action != 'login')
			{
				//we need to forward to the login page
				$this->redirect(array('controller'=>'inventory','action'=>'login'));
			}
		}
	}
	
	function beforeRender(){
		$this->set('locations',$this->Location->find('list',array('fields'=>array('Location.id','Location.location'))));
	}
	
	function search($type,$q){
		$this->set("title_for_layout","Search Results");
		
		//get the type
		$type = $this->search_types[$type];
		
		$this->set("q",$type['name']);
		$this->set("results", $this->Computer->find('all',array('conditions' => array($type['field'] => $q),'order'=>'Computer.ComputerName')));
	}
	
	function searchProgram($program){
		$this->set("title_for_layout","Search Results");
		
		//get all computers that match the program name
		$this->set("q","For Program '" . $program . "'");
		$this->set('results', $this->Programs->find('all',array('conditions' => array('Programs.program LIKE "' . $program . '%"') )));
		
		$this->render('search');
	}
}
?>