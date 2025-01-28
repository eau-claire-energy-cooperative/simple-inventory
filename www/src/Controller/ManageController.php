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
          $this->Flash->success(sprintf('%s has been saved', $newDevice['name']));
          return $this->redirect(array('action' => 'deviceTypes'));
      } else {
          $this->Flash->error('Unable to add your entry');
      }
    }
  }

  public function deleteDeviceType($id) {
    $DeviceType = $this->fetchTable('DeviceType');
    $device = $DeviceType->get($id);

    if ($DeviceType->delete($device)) {
      $this->Flash->success(sprintf('%s has been deleted', $device['name']));
      return $this->redirect(['action' => 'deviceTypes']);
    }
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

}
