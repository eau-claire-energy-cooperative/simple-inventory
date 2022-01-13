<?php

class ApplicationsController extends AppController {
	var $uses = array('Applications', 'Computer', 'Setting');
	var $helpers = array('Html','Session','Time','Form', 'Menu');

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

	public function beforeRender(){
	    parent::beforeRender();
	    $settings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
	    $this->set('settings',$settings);
	}

  public function index(){
    $this->set('title_for_layout', 'Applications');

    if($this->request->is('post')){
      $this->Applications->save($this->request->data);
      $this->Flash->success($this->request->data['Applications']['name'] . ' saved successfully');
    }

    $applications = $this->Applications->find('all', array('order'=>array('Applications.name','Applications.version')));
    $this->set('applications', $applications);

  }

  public function add_application(){
    $this->set('title_for_layout', 'Add Application');
  }

  public function assign_application(){
    //update the join table
    $joinInfo = $this->request->data['ApplicationInstall'];

    $this->Applications->query(sprintf("insert into application_installs (application_id, comp_id) values (%d, %d)", $joinInfo['application_id'], $joinInfo['comp_id']));

    $this->Flash->success('Saved');

    $this->redirect('/applications/');

  }
}
?>
