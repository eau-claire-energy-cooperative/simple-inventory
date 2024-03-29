<?php

class ApplicationsController extends AppController {
	var $uses = array('Applications', 'Computer', 'Lifecycle', 'OperatingSystem', 'Setting');
	var $helpers = array('Csv','Html','Session','Time','Form', 'Menu', "Lifecycle");
  var $components = array('RequestHandler','Session');

	public function beforeFilter(){
	  $this->_check_authenticated();
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
          $this->request->query['q'] = $this->request->data['Applications']['name'];
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

    $this->redirect('/applications?q=' . $joinInfo['application_name']);

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
    $this->set('title_for_layout', 'Application Lifecycles');

    if($this->request->is('post')){
      $this->Lifecycle->save($this->request->data);
      $this->Flash->success("Lifecycle saved");
    }

    $lifecycles = $this->Lifecycle->find('all', array('order'=>'Applications.name'));
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

  public function check_lifecycle($id){
    //helper function to reset the lifecycle last check date to today
    $this->Lifecycle->query(sprintf("update lifecycles set last_check = now() where id=%d", $id));

    $this->Flash->success("Last check date updated");

    $this->redirect('/applications/lifecycle');
  }

  public function delete_lifecycle($id){
    //no reason a lifecycle can't be deleted without any issues
    $this->Lifecycle->delete($id);

    $this->Flash->success('Lifecycle deleted successfully');

    $this->redirect('/applications/lifecycle');
  }

  public function operating_systems(){
    $this->set('title_for_layout', 'Operating Systems');

    if($this->request->is('post')){
      if($this->OperatingSystem->save($this->request->data))
      {
        $this->Flash->success('Saved ' . $this->request->data['OperatingSystem']['name'] . ' end of life date');
      }
      else {
        $this->Flash->error('Error saving end of life data for ' . $this->request->data['OperatingSystem']['name']);
      }
    }

    // operating systems are set values within devices
    // not a good way to do this natively so grab them all and sort below
    $computers = $this->Computer->find('all', array('conditions'=>array("Computer.OS != ''"), 'order'=>'Computer.OS'));

    //get a count of the different systems
    $systems = array();

    foreach($computers as $comp){
      if(!array_key_exists($comp['Computer']['OS'], $systems))
      {
        //add to array with count of 1
        $systems[$comp['Computer']['OS']] = 1;
      }
      else
      {
        //increase count by one
        $systems[$comp['Computer']['OS']] = $systems[$comp['Computer']['OS']] + 1;
      }
    }

    $this->set('allOs', $systems);

    //get any defined operating systems
    $foundOs = $this->OperatingSystem->find('list', array('fields'=>array('OperatingSystem.name', 'OperatingSystem.eol_date')));
    $this->set('definedOs', $foundOs);
  }

  public function delete_os_eol($name){
    $this->OperatingSystem->query(sprintf("delete from operating_systems where name = '%s'", $name));

    $this->Flash->success('Deleted End of life date for ' . $name);

    $this->redirect('/applications/operating_systems');
  }
}
?>
