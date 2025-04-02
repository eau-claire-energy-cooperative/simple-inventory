<?php
namespace App\Controller;
use Cake\Event\EventInterface;
use \Cake\ORM\Query;

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

  function search($type, $q){
		$this->set("title","Search Results");
    $this->_getDisplaySettings();

		//get the type
		$type = $this->search_types[$type];

    if(isset($type['active_menu']))
    {
      $this->set('active_menu', $type['active_menu']);
    }

		$this->set("q", $type['name']);
    $this->set("export_name", $type['name']);
		$this->set("results", $this->fetchTable('Computer')->find('all', ['contain'=>['DeviceType'],
                                                                      'conditions'=>[$type['field'] => $q],'order'=>'Computer.ComputerName']));

    $this->viewBuilder()->addHelper('DynamicTable');
	}

  function searchApplication($app_id){
		$this->set("title", "Search Results");
    $this->_getDisplaySettings();

		//get all computers that match the program name
    $application = $this->fetchTable('Application')->find('all', ['contain'=>['Computer', 'Computer.DeviceType'],
                                                                  'conditions' => ['Application.id'=>$app_id]])->first();
		$this->set("q", "For Application '" . $application['name'] . "'");
    $this->set("export_name", $application['name']);
		$this->set('results', $application['computer']);

		$this->render('search');
	}

  function searchLicense($type, $id){
		$this->set("title","Search Results");

    if($type == 'license'){

      $results = $this->fetchTable('License')->find('all', ['contain'=>['LicenseKey', 'LicenseKey.Computer', 'LicenseKey.Computer.DeviceType'],
                                                           'conditions'=>['License.id'=>$id]])->first();

      $this->set('license_id', $id);
	    $this->set("q", sprintf("Assigned Keys For '%s'", $results->LicenseName));
      $this->set("export_name", $results->LicenseName);
      $this->set('results', $results['license_key']);
    }
    else
    {
      $license_key = $this->fetchTable('LicenseKey')->find('all', ['contain'=>['Computer', 'Computer.DeviceType', 'License'],
                                                                   'conditions'=>['LicenseKey.id'=>$id]])->first();

      $this->set('license_id', $license_key['license_id']);
      $this->set("q",sprintf("Assigned Devices for Key '%s'", $license_key['Keycode']));
      $this->set('export_name', $license_key['license']['LicenseName']);
      $this->set('results', [$license_key]);
    }
	}

  function searchService($service){
		$this->set("title","Search Results");

		//get all computers that match the program name
		$this->set("q","For Service '" . $service . "'");

    $results = $this->fetchTable('Service')->find('all', ['contain'=>['Computer', 'Computer.DeviceType'],
                                                          'conditions'=>['Service.name LIKE "' . $service . '%"'],
                                                          'order'=>'Computer.ComputerName'])->all();

    $this->set("export_name", $service);
		$this->set('results', $results);
	}

  function _getDisplaySettings(){
    # get the display settings
    $displaySetting = $this->fetchTable('Setting')->find('all', ['conditions'=>['Setting.key'=>'home_attributes']])->first();
    $displayAttributes = explode(",", $displaySetting['value']);
    $this->set('displayAttributes', $displayAttributes);

    # set the attribute names
    $columnNames = ["CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID", "Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU",
                    "Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors", "AppUpdates"=>"Application Updates", "IPAddress"=>"IP Address",
                    "IPv6address"=>"IPv6 Address","MACAddress"=>"MAC Address"];
    $this->set('columnNames', $columnNames);
  }
}
?>
