<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class AjaxController extends AppController {

  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('Ping');
    $this->viewBuilder()->setLayout('ajax');
  }

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function addDisk($comp_id){
    $this->set('comp_id', $comp_id);
  }

  function assignApplication($app_id){
    $this->viewBuilder()->setLayout('fancybox');

    //get the application
    $application = $this->fetchTable('Application')->find('all', ['contain'=>['Computer'],
                                                                  'conditions'=>['Application.id'=>$app_id]])->first();

    //get a list of computers already assigned
    $assigned = [];
    foreach($application['computer'] as $comp){
      $assigned[] = $comp['id'];
    }

    $this->set('application', $application);

    //filter out already assigned from this list
    $allComputers = $this->fetchTable('Computer')->find('list', ['keyField'=>'id', 'valueField'=>'ComputerName',
                                                        'order'=>['Computer.ComputerName asc']]);

    // filter out already assigned, if there are any
    if(count($assigned) > 0)
    {
      $allComputers = $allComputers->where(['Computer.id NOT IN' => $assigned]);
    }

    $this->set('computers', $allComputers->toArray());
  }

  function assignLicenseKey($license_id, $license_key_id){
    $this->viewBuilder()->setLayout('fancybox');

    $this->set('license_id', $license_id);
    $this->set('license_key_id', $license_key_id);

    //get a list of all computers
    $allComputers = $this->fetchTable('Computer')->find('list', ['keyField'=>'id',
                                                                 'valueField'=>'ComputerName',
                                                                 'order'=>['Computer.ComputerName asc']])->toArray();
    $allComputers = [0=>'NO COMPUTER - UNASSIGNED'] + $allComputers;

    $this->set('computers', $allComputers);

	}

  function assignOsEol($name){
    $this->set('osName', $name);
  }

  function checkRunning($id){
	  //get the IP of the device
	  $computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

    $isRunning = $result = ['transmitted'=>1,'received'=>0];
    if($computer)
    {
	     $isRunning = $this->Ping->ping($computer['IPaddress']);
    }

    $this->set('result', $isRunning);
    $this->render('json');
	}

  function newLicenseKey($license_id){
    $this->viewBuilder()->setLayout('fancybox');

    $this->set('license_id', $license_id);
	}

  function setupCommand($id){
    $this->viewBuilder()->setLayout('fancybox');

		//get the command that goes with this id
		$command = $this->fetchTable('Command')->find('all', ['conditions'=>['Command.id'=>$id]])->first();
		$this->set('command',$command);
	}

  function setProfileImage(){
    $this->viewBuilder()->setLayout('fancybox');

    //get the current url
    $session = $this->request->getSession();
    $aUser = $this->fetchTable('User')->find('all',['conditions'=>['User.username'=>$session->read('User.username')]])->first();

    $this->set('username', $aUser['gravatar']);
	}

  function viewLifecycle($app_id){
    $Application = $this->fetchTable('Application');

    //load this application (lifecycle will follow)
    $application = $Application->find('all', ['contain'=>['Computer', 'Lifecycle'],
                                              'conditions'=>['Application.id'=>$app_id]])->first();
    $this->set('application', $application);

    //get any other version of this application
    $allVersions = $Application->find('all', ['contain'=>['Computer'],
                                              'conditions'=>['Application.name'=>$application['name']]])->all();

    // get version and total assigned info
    $totalVersions = [];
    $olderInstalls = 0;
    $newerInstalls = 0;
    foreach($allVersions as $v){
      $totalVersions[] = $v['version'];

      // if lower, count how many computers on this version
      if(version_compare($v['version'], $application['version']) == -1)
      {
        $olderInstalls = $olderInstalls + count($v['computer']);
      }
      elseif (version_compare($v['version'], $application['version']) == 1) {
        $newerInstalls = $newerInstalls + count($v['computer']);
      }
    }
    usort($totalVersions, 'version_compare');
    $this->set('total_versions', count($totalVersions));
    $this->set('highest_version', end($totalVersions));
    $this->set('older_installs', $olderInstalls);
    $this->set('newer_installs', $newerInstalls);

    $this->viewBuilder()->addHelper('Lifecycle');
    $this->viewBuilder()->addHelper('Markdown');
  }

  function wol(){
    $this->Ping->wol($_SERVER['SERVER_ADDR'], $this->request->getQuery('mac'));

    $this->set('result', ['success']);
    $this->render('json');
	}
}
?>
