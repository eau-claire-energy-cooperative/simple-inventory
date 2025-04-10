<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
  var $DEVICE_ATTRIBUTES = array("REQUIRED" => array("ComputerName"=>"Device Name", "ComputerLocation"=>"Location", "LastUpdated"=>"Last Updated"),
                                  "GENERAL" => array("CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID"),
                                  "HARDWARE" => array("Manufacturer"=>"Manufacturer","Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors","DriveSpace"=>"Drive Space","ApplicationUpdates"=>"Application Updates"),
                                  "NETWORK" => array("IPaddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACaddress"=>"MAC Address", "SupplicantUsername"=>"802.1x Supplicant Username", "SupplicantPassword"=>"802.1x Supplicant Password"));
  /**
   * Initialization hook method.
   *
   * Use this method to add common initialization code like loading components.
   *
   * e.g. `$this->loadComponent('FormProtection');`
   *
   * @return void
   */
  public function initialize(): void
  {
      parent::initialize();

      $this->loadComponent('RequestHandler');
      $this->loadComponent('Flash');

      /*
       * Enable the following component for recommended CakePHP form protection settings.
       * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
       */
      //$this->loadComponent('FormProtection');
  }

  function beforeRender(EventInterface $event){
    parent::initialize($event);

    if(!$this->viewBuilder()->hasVar('active_menu'))
    {
        $this->set('active_menu','');
    }

    $this->set('APP_VERSION', Configure::read('Version'));
  }


  protected function _check_authenticated(){
    $session = $this->request->getSession();

		//check if we are using a login method
		if(!$session->check('authenticated')){

			//check if we are using a login method
			$loginMethod = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'auth_type']])->first();

			if(isset($loginMethod) && trim($loginMethod['value']) == 'none')
			{
				//we aren't authenticating, just keep moving
				$session->write('authenticated','true');
        $session->write('User.username', 'admin');
				$session->write('User.name', 'Admin User');
			}

			//check, we may already be trying to go to the login page
			if($this->request->getParam('action') != 'login')
			{
				//we need to forward to the login page
				return $this->redirect("/inventory/login");
			}
		}
  }

  protected function _send_email($subject, $message, $recipient = ""){
    $EmailMessage = $this->fetchTable('EmailMessage');

    $m = $EmailMessage->newEmptyEntity();
    $m->subject = $subject;
    $m->message = $message;
    $m->recipient = $recipient;

    $EmailMessage->save($m);
  }

  protected function _saveLog($message){
    $Log = $this->fetchTable('Logs');

    $aLog = $Log->newEmptyEntity();
    $aLog->LOGGER = 'Website';
    $aLog->LEVEL = 'INFO';
    $aLog->MESSAGE = $message;
    $aLog->DATED = date("Y-m-d H:i:s",time());

    $Log->save($aLog);
	}
}
