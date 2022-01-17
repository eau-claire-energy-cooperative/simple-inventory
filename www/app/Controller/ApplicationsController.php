<?php

class ApplicationsController extends AppController {
	var $uses = array('Applications', 'Computer', 'Lifecycle', 'Setting');
	var $helpers = array('Html','Session','Time','Form', 'Menu', "Lifecycle");

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
        //try and save this application, throw an error if already exists
        if($this->Applications->save($this->request->data))
        {
          $this->Flash->success($this->request->data['Applications']['name'] . ' saved successfully');
        }
        else {
          $this->Flash->error($this->Applications->validationErrors['name'][0]);
        }

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

  public function unassign_application($app_id, $comp_id){
    $this->Applications->query(sprintf("delete from application_installs where application_id = %d and comp_id = %d", $app_id, $comp_id));

    $this->Flash->success('Application removed');

    $this->redirect('/inventory/moreInfo/' . $comp_id);
  }

  public function upgrade_application(){
    //load the lifecycle from the ID
    $lifecycle = $this->Lifecycle->find('first', array('conditions'=>array('Lifecycle.id'=>$this->request->data['Lifecycle']['id'])));
    $new_version = $this->request->data['Lifecycle']['version'];

    //check if we can upgrade this application
    if(!$this->Applications->find('first',array('conditions'=>array('Applications.name'=>$lifecycle['Applications']['name'], 'Applications.version'=>$new_version))))
    {
      //upgrade the version number
      $lifecycle['Applications']['version'] = $new_version;
      $this->Applications->save($lifecycle['Applications']);

      //reset the lifecycle date
      $this->Lifecycle->query(sprintf("update lifecycles set last_check = now() where id=%d", $lifecycle['Lifecycle']['id']));

      $this->Flash->success($lifecycle['Applications']['name'] . ' upgraded to ' . $new_version);
    }
    else
    {
      $this->Flash->error($lifecycle['Applications']['name'] . " version " . $new_version . " already exists");
    }

    $this->redirect('/applications/lifecycle');
  }

  public function delete_application($app_id){
    //check if this application has computers assigned
    $application = $this->Applications->find('first', array('conditions'=>array('Applications.id'=>$app_id)));

    if(count($application['Computer']) > 0)
    {
      $this->Flash->error($application['Applications']['full_name'] . ' cannot be deleted, it has computers assigned');
    }
    else if($application['Lifecycle']['id'] != NULL)
    {
      $this->Flash->error($application['Applications']['full_name'] . ' cannot be deleted, it has a lifecycle assigned');
    }
    else
    {
      $this->Applications->delete($app_id);
      $this->Flash->success($application['Applications']['full_name'] . ' successfully deleted');
    }


    $this->redirect('/applications/');
  }

  public function lifecycle(){
    $this->set('title_for_layout', 'Software Lifecycles');

    if($this->request->is('post')){
      $this->Lifecycle->save($this->request->data);
      $this->Flash->success("Lifecycle saved");
    }

    $lifecycles = $this->Lifecycle->find('all');
    $this->set('lifecycles', $lifecycles);

  }

  public function add_lifecycle(){
    $this->set('title_for_layout', 'Create Software Lifecycle');

    $applications = $this->Applications->find('list', array('fields'=>array('Applications.id', 'Applications.full_name'),
                                                            'order'=>array('Applications.full_name asc')));
    $this->set('applications', $applications);
  }

  public function edit_lifecycle($id){
    $this->set('title_for_layout', 'Edit Software Lifecycle');

    $applications = $this->Applications->find('list', array('fields'=>array('Applications.id', 'Applications.full_name'),
                                                            'order'=>array('Applications.full_name asc')));
    $this->set('applications', $applications);

    $this->set('lifecycle', $this->Lifecycle->find('first', array('conditions'=>array('Lifecycle.id'=>$id))));
  }
}
?>
