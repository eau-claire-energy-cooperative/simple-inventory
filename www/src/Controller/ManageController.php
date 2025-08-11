<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class ManageController extends AppController {

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

  public function addDeviceType() {
    $this->set('title','Add Device Type');
    $this->set('allowedAttributes', array_merge($this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    if ($this->request->is('post')) {
      $DeviceType = $this->fetchTable('DeviceType');

      $newDevice = $DeviceType->newEntity($this->request->getData());
      $newDevice->attributes = implode(",", $this->request->getData('attributes'));

      if ($DeviceType->save($newDevice)) {
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf('Created device type [%s](device_type:%s)', $newDevice['name'], $newDevice['name']));
        $this->Flash->success(sprintf('%s has been saved', $newDevice['name']));
        return $this->redirect(array('action' => 'deviceTypes'));
      } else {
          $this->Flash->error('Unable to add your entry');
      }
    }
  }

  function assignLicenseKey($license_key_id, $device_id){

    //compare quantity to make sure a license is available
    $license_key = $this->fetchTable('LicenseKey')->find('all', ['contain'=>['Computer'],
                                             'conditions'=>['LicenseKey.id'=>$license_key_id]])->first();

    if(count($license_key['computer']) < $license_key['Quantity'])
    {
      $newKey = $this->fetchTable('ComputerLicense')->insertQuery()->insert(['device_id', 'license_id'])
                                          ->values(['device_id'=>$device_id, 'license_id'=>$license_key_id])
                                          ->execute();

      $this->Flash->success('License Assigned');
    }
    else
    {
      $this->Flash->error('No more keys available');
    }

    return $this->redirect(sprintf('/inventory/more_info/%d', $device_id));
  }

  public function availableLicenses($id){
    $this->set('title','Available Licenses');
    $this->set('active_menu', 'applications');

    $device = $this->fetchTable('Computer')->get($id);

    // get all the license keys
    $license_keys = $this->fetchTable('LicenseKey')->find('all', ['contain'=>['Computer', 'License'],
                                                                  'order'=>['License.LicenseName'=>'asc']])->all();
    $available_keys = [];

    foreach($license_keys as $key){
      // check if a key is available
      if(count($key['computer']) < $key['Quantity'])
      {
        $available_keys[] = $key;
      }
    }

    $this->set('computer', $device);
    $this->set('available_keys', $available_keys);
  }

  public function commands(){
    $this->set('title','Scheduled Tasks');
    $this->set('active_menu', 'schedule');

    //get all of the commands that can be scheduled
    $all_commands = $this->fetchTable('Command')->find('all', ['order'=>['Command.name']])->all();
    $this->set('all_commands',$all_commands);

    //get all of the current schedules
    $all_schedules = $this->fetchTable('Schedule')->find('all',['contain'=>['Command'],
                                                                'order'=>['Command.name']])->all();
    $this->set('all_schedules',$all_schedules);
  }

  public function deleteDeviceType($id) {
    $DeviceType = $this->fetchTable('DeviceType');
    $device = $DeviceType->get($id);

    if ($DeviceType->delete($device)) {
      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf('Deleted device type %s', $device['name']));
      $this->Flash->success(sprintf('%s has been deleted', $device['name']));
    }
    else
    {
      $this->Flash->error(sprintf('Error removing %s', $device['name']));
    }

    return $this->redirect(['action' => 'deviceTypes']);
  }

  public function deleteLicense($id){
    $License = $this->fetchTable('License');

    $license = $License->find('all', ['contain'=>['LicenseKey'],
                                      'conditions'=>['License.id'=>$id]])->first();

    if(count($license['license_key']) == 0)
    {
      if($License->delete($license))
      {
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf('Deleted license %s', $license['LicenseName']));
        $this->Flash->success(sprintf("%s Deleted", $license['LicenseName']));
      }
      else
      {
        $this->Flash->error(sprintf('Error deleting %s', $license['LicenseName']));
      }

      return $this->redirect("/manage/licenses");
    }
    else
    {
      $this->Flash->error('Cannot delete a license with active keys');
      return $this->redirect("/manage/view_license/" . $id);
    }
  }

  function deleteLicenseKey($license_id, $license_key_id){
    $LicenseKey = $this->fetchTable('LicenseKey');

    $license_key = $LicenseKey->find('all', ['contain'=>['Computer', 'License'],
                                             'conditions'=>['LicenseKey.id'=>$license_key_id]])->first();

    // cannot delete key with devices assigned
    if(count($license_key['computer']) == 0)
    {

	    if ($LicenseKey->delete($license_key))
      {
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf('Deleted license key assigned to %s', $license_key['license']['LicenseName']));
	      $this->Flash->success('License Key Deleted');
	    }
      else
      {
        $this->Flash->error('Error deleting license key');
      }

    }
    else
    {
      $this->Flash->error('Cannot delete key with assigned devices');
    }

    $this->redirect('/manage/view_license/' . $license_id);
	}

  public function deviceTypes() {
    $this->set('title','Device Types');
    $this->set('device_types', $this->fetchTable('DeviceType')->find('all', ['contain'=>['Computer'],
                                                                             'order'=>['name'=>'ASC']])->all());
  }

  public function editDeviceType($id) {
    $this->set('title','Edit Device Type');
    $this->set('allowedAttributes', array_merge($this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    $DeviceType = $this->fetchTable('DeviceType');
    $device = $DeviceType->get($id);

    if ($this->request->is('get')) {
      $this->set('device', $device);
    }
    else
    {
      $DeviceType->patchEntity($device, $this->request->getData());
      $device->attributes = implode(",", $this->request->getData('attributes'));

      if ($DeviceType->save($device)) {
        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf('Updated device type [%s](device_type:%s)', $device['name'], $device['name']));
        $this->Flash->success(sprintf('%s has been updated', $device['name']));
        $this->redirect(array('action' => 'deviceTypes'));
      }
      else
      {
        $this->Flash->error(sprintf('Unable to update %s', $device['name']));
      }

      return $this->redirect('/admin/deviceTypes');
    }
  }

  function licenses(){
      $this->set('title', 'Licenses');
      $this->set('active_menu', 'applications');
      $this->viewBuilder()->addHelper('License');

      //get a list of all licenses
      $licenses = $this->fetchTable('License')->find('all', ['contain'=>'LicenseKey',
                                                             'order'=>['License.LicenseName'=>'asc']])->all();
      $this->set('licenses', $licenses);

  }

  function editLicense($id = null){
    $this->set('title', 'Edit License');
    $License = $this->fetchTable('License');

    if($this->request->is('post') || $this->request->is('put'))
    {
      $license = $License->newEntity($this->request->getData());

      // check for no expiration flag
      if($this->request->getData("NoExpiration") == "0")
      {
        $license->ExpirationDate = $this->request->getData('ExpirationDate');
      }
      else
      {
        $license->ExpirationDate = null;
      }

      $License->save($license);
      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf('License %s saved', $license->LicenseName));
      $this->Flash->success(sprintf("%s Saved", $license->LicenseName));

      return $this->redirect("/manage/view_license/" . $license->id);
    }
    else
    {
      $license = null;
      if($id != null)
      {
        $license = $License->find('all', ['contain'=>['LicenseKey', 'LicenseKey.Computer'],
                                          'conditions'=>['License.id' => $id],
                                          'recursive'=>1])->first();
      }
      else
      {
        $license = $License->newEmptyEntity();
      }

      $this->set('license', $license);
    }
  }

  function resetLicense($device_id, $license_key_id){

    // delete the link that joins the license to the device
    $this->fetchTable('ComputerLicense')->deleteQuery()->where(['device_id'=>$device_id, 'license_id'=>$license_key_id])->execute();
    $this->Flash->success("Unassigned License Key");

    // redirect back to the original URL
    return $this->redirect($this->request->referer(true));
  }

  function schedule($id = NULL){
    $Schedule = $this->fetchTable('Schedule');

    if($this->request->is('post'))
    {
      #setup the schedule model
      $schedule = $Schedule->newEntity($this->request->getData());

      //get all of the parameters
      $schedule_params = 'array(';
      if($this->request->getData('parameter_list') != '')
      {
        $parameters = explode(',', $this->request->getData('parameter_list'));

        foreach($parameters as $param){
          $schedule_params = $schedule_params . "'" . $param . "'=>'" . $this->request->getData('param_' . strtolower(str_replace(' ', '_',$param))) . "',";
        }

        $schedule_params = substr($schedule_params,0,-1);
      }

      $schedule_params = $schedule_params . ')';
      $schedule->parameters = $schedule_params;
      $Schedule->save($schedule);
      $schedule = $Schedule->loadInto($schedule, ['Command']);

      $this->_saveLog($this->request->getSession()->read('User.username'),
                      sprintf('Created schedule %s for %s', $schedule['command']['name'], $schedule['schedule']));
      $this->Flash->success('Schedule Created');
    }
    else
    {
      if($id != NULL)
      {
        $schedule = $Schedule->find('all', ['contain'=>['Command'],
                                            'conditions'=>['Schedule.id'=>$id]])->first();
        $Schedule->delete($schedule);

        $this->_saveLog($this->request->getSession()->read('User.username'),
                        sprintf('Deleted schedule %s for %s', $schedule['command']['name'], $schedule['schedule']));
        $this->Flash->success('Schedule Removed');
      }
    }

    return $this->redirect('/manage/commands');
	}

  function viewLicense($id){
    $this->set('title', 'License Detail');
    $this->set('active_menu', 'applications');
    $this->viewBuilder()->addHelper('License');
    $License = $this->fetchTable('License');

    if($this->request->is('post')){
      // assign a new license key
      if($this->request->getData('license_key_id') != null)
      {
        //compare quantity to make sure a license is available
        $license_key = $this->fetchTable('LicenseKey')->find('all', ['contain'=>['Computer'],
                                                 'conditions'=>['LicenseKey.id'=>$this->request->getData('license_key_id')]])->first();

        if(count($license_key['computer']) < $license_key['Quantity'])
        {
          $newKey = $this->fetchTable('ComputerLicense')->insertQuery()->insert(['device_id', 'license_id'])
                                              ->values(['device_id'=>$this->request->getData('computer'), 'license_id'=>$this->request->getData('license_key_id')])
                                              ->execute();

          $this->Flash->success('License Assigned');
        }
        else
        {
          $this->Flash->error('No more keys available');
        }
      }
      else
      {
        if($this->request->getData('Quantity') > 0)
        {
          $LicenseKey = $this->fetchTable('LicenseKey');
          $newKey = $LicenseKey->newEntity($this->request->getData());

          $LicenseKey->save($newKey);
          $this->Flash->success('License Key Added');
        }
        else
        {
          $this->Flash->error('Quantity must be greater than 0');
        }
      }
    }

    //get the license to display
    $license = $License->find('all', ['contain'=>['LicenseKey', 'LicenseKey.Computer'],
                                      'conditions'=>['License.id' => $id],
                                      'recursive'=>1])->first();

    $this->set('license', $license);

    // add helper
    $this->viewBuilder()->addHelper('Markdown');
  }

}
