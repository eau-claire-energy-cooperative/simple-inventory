<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    var $DEVICE_ATTRIBUTES = array("REQUIRED" => array("ComputerName"=>"Device Name", "ComputerLocation"=>"Location", "LastUpdated"=>"Last Updated"),
                                  "GENERAL" => array("CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID"),
                                  "HARDWARE" => array("Manufacturer"=>"Manufacturer","Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors","DriveSpace"=>"Drive Space","ApplicationUpdates"=>"Application Updates"),
                                  "NETWORK" => array("IPaddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACaddress"=>"MAC Address", "SupplicantUsername"=>"802.1x Supplicant Username", "SupplicantPassword"=>"802.1x Supplicant Password"));

    public function beforeRender(){
        if(!isset($this->viewVars['active_menu']))
        {
            $this->set('active_menu','');
        }
    }

    protected function _send_email($subject, $message){
      $this->loadModel('EmailMessage');

      //create an email message and add it to the queue to be sent
      $this->EmailMessage->create();
			$this->EmailMessage->set('subject',$subject);
			$this->EmailMessage->set('message',$message);
			$this->EmailMessage->save();
    }

    protected function _check_authenticated(){
      $this->loadModel('Setting');

  		//check if we are using a login method
  		if(!$this->Session->check('authenticated')){
  			//check if we are using a login method
  			$loginMethod = $this->Setting->find('first',array('conditions'=>array('Setting.key'=>'auth_type')));

  			if(isset($loginMethod) && trim($loginMethod['Setting']['value']) == 'none')
  			{
  				//we aren't authenticating, just keep moving
  				$this->Session->write('authenticated','true');
          $this->Session->write('User.username', 'admin');
  				$this->Session->write('User.name', 'Admin User');
  			}
  			//check, we may already be trying to go to the login page
  			else if($this->action != 'login')
  			{
  				//we need to forward to the login page
  				$this->redirect(array('controller'=>'inventory','action'=>'login'));
  			}
  		}
    }
}
