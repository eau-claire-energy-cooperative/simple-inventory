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

    $this->set('application', $application);
  }

  function assignLicenseKey($license_id, $license_key_id){
    $this->viewBuilder()->setLayout('fancybox');

    $this->set('license_id', $license_id);
    $this->set('license_key_id', $license_key_id);

	}

  function assignOsEol($name){
    $this->set('osName', $name);
  }

  function checkRunning($id){
    $this->viewBuilder()->setClassName("Json");
	  //get the IP of the device
	  $computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

    $isRunning = $result = ['transmitted'=>1,'received'=>0];
    if($computer)
    {
	     $isRunning = $this->Ping->ping($computer['IPaddress']);
    }

    $this->set('result', $isRunning);
    $this->viewBuilder()->setOption('serialize', 'result');
	}

  function extendCheckout($id){
    $this->viewBuilder()->setLayout('fancybox');

    $req = $this->fetchTable('CheckoutRequest')->find('all', ['contain'=>['Computer'],
                                                              'conditions'=>['CheckoutRequest.id'=>$id]])->first();

    $this->set('req', $req);
  }

  function searchApplicationList(){
    $this->viewBuilder()->setClassName("Json");
    // escape character for sprintf is %
    $applications = $this->fetchTable('Application')->find('all', ['conditions'=>[sprintf("Application.name LIKE '%%%s%%'", $this->request->getQuery('q'))],
                                                           'order'=>['Application.name asc']])->all();

    // put in the format value=id, text=name
    $result = [];
    foreach($applications  as $app){
      $result[] = ['value'=>$app['id'], 'text'=>$app['full_name']];
    }

    $this->set('result', $result);
    $this->viewBuilder()->setOption('serialize', 'result');
  }

  function searchDeviceList($app_filter=null){
    $this->viewBuilder()->setClassName("Json");
    // escape character for sprintf is %
    $deviceQ = $this->fetchTable('Computer')->find('all', ['conditions'=>[sprintf("Computer.ComputerName LIKE '%%%s%%'", $this->request->getQuery('q'))],
                                                           'order'=>['Computer.ComputerName asc']]);

    // if app filter given, filter out devices assigned to this app
    if(isset($app_filter))
    {
      $deviceQ->notMatching('Application', function($q) use ($app_filter){
        return $q->where(['Application.id'=>intval($app_filter)]);
      });
    }

    $devices = $deviceQ->all();
    // put in the format value=id, text=name
    $result = [];
    foreach($devices as $aDevice){
      $result[] = ['value'=>$aDevice['id'], 'text'=>$aDevice['ComputerName']];
    }

    $this->set('result', $result);
    $this->viewBuilder()->setOption('serialize', 'result');
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

  function viewHistory($hist_id){
    $this->viewBuilder()->setLayout('fancybox');

    $entry = $this->fetchTable('ComputerHistory')->find('all', ['contain'=>['Computer'],
                                                                'conditions'=>['ComputerHistory.id'=>$hist_id]])->first();
    $this->set('entry', $entry);
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

  function uploadDrivers($id){
	  $this->viewBuilder()->setLayout('fancybox');
		$computer = $this->fetchTable('Computer')->find('all', ['conditions'=>['Computer.id'=>$id]])->first();

		$this->set('computer',$computer);
	}

  function wol(){
    $this->viewBuilder()->setClassName("Json");
    $this->Ping->wol($_SERVER['SERVER_ADDR'], $this->request->getQuery('mac'));

    $this->set('result', ['success']);

    $this->viewBuilder()->setOption('serialize', 'result');
	}
}
?>
