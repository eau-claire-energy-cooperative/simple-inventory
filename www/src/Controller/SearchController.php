<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class SearchController extends AppController {
  var $search_types = [["name"=>"Tag","field"=>"Computer.ComputerLocation"],
  						         ['name'=>'Model','field'=>'Computer.Model'],
  						         ['name'=>'OS','field'=>'Computer.OS'],
  						         ['name'=>'Memory','field'=>'Computer.Memory'],
  						         ['name'=>'Monitors','field'=>'Computer.NumberOfMonitors'],
                       ['name'=>'Device Type', 'field'=>'DeviceType.name'],
                       ['name'=>'Checkout Enabled', 'field'=>'Computer.CanCheckout', 'active_menu'=>'checkout']];

  function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $this->_check_authenticated();
  }

  function beforeRender(EventInterface $event){
    parent::beforeRender($event);

    // set attributes
    $this->set('requiredAttributes', $this->DEVICE_ATTRIBUTES['REQUIRED']);
    $this->set('allAttributes', array_merge($this->DEVICE_ATTRIBUTES['REQUIRED'], $this->DEVICE_ATTRIBUTES['GENERAL'], $this->DEVICE_ATTRIBUTES['HARDWARE'], $this->DEVICE_ATTRIBUTES['NETWORK']));

    // set the locations
    $this->set('locations',$this->fetchTable('Location')->find('list',['keyField'=>'id','valueField'=>'location'])->toArray());

    // find settings before rendering
    $settings = $this->fetchTable('Setting')->find('list', ['keyField'=>'key', 'valueField'=>'value'])->toArray();
    $this->set("settings", $settings);
  }

  function searchLicense($type, $id){
		$this->set("title","Search Results");

    if($type == 'license'){

      $results = $this->fetchTable('License')->find('all', ['contain'=>['LicenseKey', 'LicenseKey.Computer', 'LicenseKey.Computer.DeviceType'],
                                                           'conditions'=>['License.id'=>$id]])->first();

      $this->set('license_id', $id);
	    $this->set("q", sprintf("Assigned Keys For '%s'", $results->LicenseName));
      $this->set('results', $results['license_key']);
    }
    else
    {
      $license_key = $this->fetchTable('LicenseKey')->find('all', ['contain'=>['Computer', 'Computer.DeviceType'],
                                                                   'conditions'=>['LicenseKey.id'=>$id]])->first();

      $this->set('license_id', $license_key['license_id']);
      $this->set("q",sprintf("Assigned Devices for Key '%s'", $license_key['Keycode']));
      $this->set('results', [$license_key]);
    }
	}
}
?>
