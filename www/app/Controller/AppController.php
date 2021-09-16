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
    var $DEVICE_ATTRIBUTES = array("REQUIRED" => array("ComputerName"=>"Device Name", "Location"=>"Location", "LastUpdated"=>"Last Updated"),
                                  "GENERAL" => array("CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID"),
                                  "HARDWARE" => array("Manufacturer"=>"Manufacturer","Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors","DriveSpace"=>"Drive Space","ApplicationUpdates"=>"Application Updates"),
                                  "NETWORK" => array("IPaddress"=>"IP Address","IPv6address"=>"IPv6 Address","MACaddress"=>"MAC Address"));

    public function beforeRender(){
        if(!isset($this->viewVars['active_menu']))
        {
            $this->set('active_menu','');
        }
    }
}
