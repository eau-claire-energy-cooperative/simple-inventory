<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;

class ApplicationsController extends AppController {

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    // find settings before rendering
    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
    $this->set("settings", $settings);
  }

  public function addApplication(){
    $this->set('title_for_layout', 'Add Application');
  }

  public function addLifecycle(){
    $this->set('title', 'Create Software Lifecycle');

    $this->set('today', FrozenTime::now());
  }

  public function assignApplication(){
    //update the join table
    $connection = ConnectionManager::get('default');

    $connection->execute("insert into application_installs (application_id, comp_id) values (?, ?)",
                                     [$this->request->getData('application_id'), $this->request->getData('comp_id')],
                                     ['integer', 'integer']);

    $this->Flash->success('Saved');

    return $this->redirect('/applications?q=' . $this->request->getData('application_name'));
  }

  public function checkLifecycle($id){

    $Lifecycle = $this->fetchTable('Lifecycle');
    $lifecycle = $Lifecycle->find('all', ['contain'=>['Application'],
                                          'conditions'=>['Lifecycle.id'=>$id]])->first();

    //helper function to reset the lifecycle last check date to today
    $Lifecycle->updateQuery()->set(['last_check'=>FrozenTime::now()])->where(['id'=>$id])->execute();

    $this->_saveLog($this->request->getSession()->read('User.username'),
                    sprintf("Lifecycle check updated for %s", $lifecycle['application']['full_name']));
    $this->Flash->success("Last check date updated");

    return $this->redirect('/applications/lifecycle');
  }

  public function deleteApplication($app_id){
    //check if this application has computers assigned
    $Application = $this->fetchTable('Application');
    $application = $Application->find('all', ['contain'=>['Computer', 'Lifecycle'],
                                                                  'conditions'=>['Application.id'=>$app_id]])->first();

    if(count($application['computer']) > 0)
    {
      $this->Flash->error(sprintf('%s cannot be deleted, it has computers assigned', $application['full_name']));
    }
    else if(isset($application['lifecycle']))
    {
      $this->Flash->error(sprintf('%s cannot be deleted, it has a lifecycle assigned', $application['full_name']));
    }
    else
    {
      $Application->delete($application);
      $this->Flash->success(sprintf('%s successfully deleted', $application['full_name']));
    }

    return $this->redirect('/applications/');
  }

  public function deleteOsEol($name){
    $this->fetchTable('OperatingSystem')->deleteQuery()->where(["name"=>$name])->execute();

    $this->Flash->success(sprintf('Deleted End of life date for %s', $name));

    return $this->redirect('/applications/operating_systems');
  }

  public function deleteLifecycle($id){
    $Lifecycle = $this->fetchTable('Lifecycle');

    //no reason a lifecycle can't be deleted
    $lifecycle = $Lifecycle->find('all', ['contain'=>['Application'],
                                          'conditions'=>['Lifecycle.id'=>$id]])->first();
    $Lifecycle->delete($lifecycle);

    $this->_saveLog($this->request->getSession()->read('User.username'),
                    sprintf('Lifecycle deleted for application %s', $lifecycle['application']['full_name']));
    $this->Flash->success('Lifecycle deleted successfully');

    $this->redirect('/applications/lifecycle');
  }

  public function editLifecycle($id){
    $this->set('title', 'Edit Software Lifecycle');

    $this->set('lifecycle', $this->fetchTable('Lifecycle')->find('all', ['contain'=>['Application'],
                                                                         'conditions'=>['Lifecycle.id'=>$id]])->first());
  }

  public function index(){
    $this->set('title', 'Applications');

    $Application = $this->fetchTable('Application');

    // set URL query - if it exists
    if($this->request->getQuery('q') != null)
    {
      $this->set('q', $this->request->getQuery('q'));
    }
    else
    {
      $this->set('q', '');
    }

    // save new app, if POST
    if($this->request->is('post'))
    {
      $app = $Application->newEntity($this->request->getData());

      if($Application->save($app))
      {
        $this->set('q', $app->name);
        $this->Flash->success(sprintf('%s saved successfully', $app->name));
      }
      else
      {
        $this->Flash->error(sprintf('Failed to save %s', $app->name));
      }
    }

    $applications = $Application->find('all', ['contain'=>['Computer', 'Lifecycle'],
                                              'order'=>['Application.name','Application.version']]);

    $this->set('applications', $applications);
  }

  public function lifecycle(){
    $this->set('title', 'Application Lifecycles');

    $Lifecycle = $this->fetchTable('Lifecycle');

    if($this->request->is('post')){
      $lifecycle = null;

      // check if this is existing
      if(empty($this->request->getData('id')))
      {
        // create new
        $lifecycle = $Lifecycle->newEntity($this->request->getData());
      }
      else
      {
        // patch existing
        $lifecycle = $Lifecycle->get($this->request->getData('id'));
        $Lifecycle->patchEntity($lifecycle, $this->request->getData());
      }

      //save the lifecycle - reload associations
      $Lifecycle->save($lifecycle);
      $lifecycle = $Lifecycle->loadInto($lifecycle, ['Application']);

      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf('Lifecycle created for %s', $lifecycle['application']['full_name']));
      $this->Flash->success("Lifecycle saved");
    }

    $lifecycles = $Lifecycle->find('all', ['contain'=>['Application'],
                                           'order'=>'Application.name'])->all();
    $this->set('lifecycles', $lifecycles);

    $this->viewBuilder()->addHelper('Lifecycle');

  }

  public function operatingSystems(){
    $this->set('title', 'Operating Systems');

    $OperatingSystem = $this->fetchTable('OperatingSystem');

    if($this->request->is('post')){
      $os = $OperatingSystem->newEntity($this->request->getData());

      if($OperatingSystem->save($os))
      {
        $this->Flash->success(sprintf('Saved %s end of life date', $os->name));
      }
      else {
        $this->Flash->error(sprintf('Error saving end of life data for %s', $os->name));
      }
    }

    // operating systems are set values within devices
    // not a good way to do this natively so grab them all and sort below
    $computers = $this->fetchTable('Computer')->find('all', ['conditions'=>["Computer.OS != ''"],
                                                             'order'=>'Computer.OS'])->all();

    //get a count of the different systems
    $systems = [];

    foreach($computers as $comp){
      if(!array_key_exists($comp['OS'], $systems))
      {
        //add to array with count of 1
        $systems[$comp['OS']] = 1;
      }
      else
      {
        //increase count by one
        $systems[$comp['OS']] = $systems[$comp['OS']] + 1;
      }
    }

    $this->set('allOs', $systems);

    //get any defined operating systems
    $foundOs = $OperatingSystem->find('list', ['keyField'=>'name', 'valueField'=>'eol_date'])->toArray();
    $this->set('definedOs', $foundOs);

    $this->viewBuilder()->addHelper('OperatingSystem');
  }

  public function unassignApplication($app_id, $comp_id){
    $connection = ConnectionManager::get('default');

    // remove from join table directly
    $connection->execute("delete from application_installs where application_id = ? and comp_id = ?",
                         [$app_id, $comp_id],
                         ['integer', 'integer']);

    $this->Flash->success('Application removed');

    return $this->redirect('/inventory/moreInfo/' . $comp_id);
  }

  public function upgradeApplication(){
    $Lifecycle = $this->fetchTable('Lifecycle');
    $Application = $this->fetchTable('Application');

    //load the lifecycle from the ID
    $lifecycle = $Lifecycle->find('all', ['contain'=>['Application'],
                                          'conditions'=>['Lifecycle.id'=>$this->request->getData('id')]])->first();
    $new_version = $this->request->getData('version');

    //check if we can upgrade this application
    $app_exists = $Application->find('all', ['conditions'=>['Application.name'=>$lifecycle['application']['name'], 'Application.version'=>$new_version]]);
    if($app_exists->count() == 0)
    {
      //upgrade the version number
      $lifecycle['application']['version'] = $new_version;

      $Application->save($lifecycle['application']);

      //reset the lifecycle date
      $Lifecycle->updateQuery()->set(['last_check'=>FrozenTime::now()])->where(['id'=>$lifecycle['id']])->execute();

      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf('%s lifecycle upgraded to %s', $lifecycle['application']['name'], $new_version));
      $this->Flash->success(sprintf('%s upgraded to %s', $lifecycle['application']['name'], $new_version));
    }
    else
    {
      $this->Flash->error(sprintf("%s version %s already exists", $lifecycle['application']['name'], $new_version));
    }

    return $this->redirect('/applications/lifecycle');
  }
}
?>
