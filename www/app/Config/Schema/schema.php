<?php
class AppSchema extends CakeSchema {

  public function before($event = array()) {
    $db = ConnectionManager::getDataSource($this->connection);
    $db->cacheSources = false;

		return true;
	}

	public function after($event = array()) {
    if(isset($event['create']))
    {
      switch ($event['create']){
        case 'location':
          //when creating the location table, insert some default locations
          $location = ClassRegistry::init('Location');
          $location->create();
          $location->saveMany(array(array('Location'=>array('location'=>'IT','is_default'=>"false")),
                        array('Location'=>array('location'=>'Human Resources','is_default'=>"false")),
                        array('Location'=>array('location'=>'Office','is_default'=>"true")),
                        array('Location'=>array('location'=>'Operations','is_default'=>"false"))));

          break;
        case 'device_types':
            //when creating the location table, insert some default locations
            $dType = ClassRegistry::init('DeviceType');
            $dType->create();
            $dType->saveMany(array(array('DeviceType'=>array('id'=>1,'name'=>'computer', 'icon'=>'desktop_windows','attributes'=>"", "check_running"=>'true',
                            "allow_decom"=>"true", "exclude_ad_sync"=>'false'))));

            break;
        case 'users':
          //create a default user for first time login
          $user = ClassRegistry::init('User');
          $user->create();
          $user->save(array('User'=>array('name'=>'Temp','username'=>'test','password'=>'1a1dc91c907325c69271ddf0c944bc72','email'=>'test@domain.com')));
          break;

        case 'settings':
          //create some default settings
          $settings = ClassRegistry::init('Setting');
          $settings->create();
          $settings->saveMany(array(array('Setting'=>array('key'=>'smtp_server','value'=>'')),
                        array('Setting'=>array('key'=>'smtp_user','value'=>'')),
                        array('Setting'=>array('key'=>'smtp_pass','value'=>'')),
                        array('Setting'=>array('key'=>'smtp_auth','value'=>'')),
                        array('Setting'=>array('key'=>'outgoing_email','value'=>'admin@domain.com')),
                        array('Setting'=>array('key'=>'computer_ignore_list','value'=>'')),
                        array('Setting'=>array('key'=>'auth_type','value'=>'local')),
                        array('Setting'=>array('key'=>'ldap_host','value'=>'')),
                        array('Setting'=>array('key'=>'ldap_port','value'=>'389')),
                        array('Setting'=>array('key'=>'ldap_basedn','value'=>'')),
                        array('Setting'=>array('key'=>'ldap_user','value'=>'')),
                        array('Setting'=>array('key'=>'ldap_password','value'=>'')),
                        array('Setting'=>array('key'=>'ldap_computers_basedn','value'=>'')),
                        array('Setting'=>array('key'=>'show_computer_commands','value'=>'true')),
                        array('Setting'=>array('key'=>'domain_username','value'=>'administrator')),
                        array('Setting'=>array('key'=>'domain_password','value'=>'password')),
                        array('Setting'=>array('key'=>'search_domain','value'=>'domain.local')),
                        array('Setting'=>array('key'=>'api_auth_key','value'=>'pass')),
                        array('Setting'=>array('key'=>'shutdown_message','value'=>'The Administrator has initiated a shutdown of your PC')),
                        array('Setting'=>array('key'=>'display_attributes','value'=>'ComputerName,Location,CurrentUser,SerialNumber,AppUpdates,Manufacturer,Model,OS,CPU,Memory,NumberOfMonitors,IPAddress,MACAddress,DriveSpace,Status')),
                        array('Setting'=>array('key'=>'home_attributes','value'=>'CurrentUser,Model,OS,Memory')),
                        array('Setting'=>array('key'=>'enable_device_checkout','value'=>'false')),
                        array('Setting'=>array('key'=>'device_checkout_location','value'=>'')),
                        array('Setting'=>array('key'=>'computer_auto_add','value'=>'false'))));
          break;
        case 'commands':
          //create some of the default commands
          $command = ClassRegistry::init('Command');
          $command->create();
          $command->saveMany(array(array('Command'=>array('name'=>'Monitored Applications','parameters'=>'','description'=>'Generate a report of any applications installed that have been flagged for monitoring in the Applications area.')),
                      array('Command'=>array('name'=>'Wake Computer','parameters'=>'Computer Name','description'=>'Wake a specific computer via a WOL packet at a given time.')),
                      array('Command'=>array('name'=>'Send Emails','parameters'=>'','description'=>'This command should be kept running at all times. It will clear the email queue by sending emails to system administrators.')),
                      array('Command'=>array('name'=>'Check Disk space','parameters'=>'Minimum Space Threshold','description'=>'Check the disk space available on all computers. Any that do not contain the minimum amount of space (in percent) will generate an email to the system administrator.')),
                      array('Command'=>array('name'=>'Remove Old Applications','parameters'=>'','description'=>'Removes Applications that no longer are installed on any computer from the database.')),
                      array('Command'=>array('name'=>'Purge Decommissioned Devices','parameters'=>'Years','description'=>'Removes devices that have been decommissioned when the decommission date is greater than the given number of years.')),
                      array('Command'=>array('name'=>'Purge Checkout Requests','parameters'=>'','description'=>'Removes inactive checkout requests (approved or unapproved) where the checkout window has expired.')),
                      array('Command'=>array('name'=>'Purge Logs','parameters'=>'Years','description'=>'Automatically removes logs from the system older than the given number of years.')));
          break;
      }
    }
	}

	public $application_installs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $applications = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'version' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'monitoring' => array('type' => 'string', 'null' => true, 'default' => 'false', 'length' => 12, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

  public $checkout_request = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
    'employee_name' => array('type' => 'string', 'null' => false, 'length'=>50, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
    'employee_email' => array('type' => 'string', 'null' => false, 'length'=>200, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
    'check_out_date' => array('type' => 'timestamp', 'null' => false, 'default' => null),
    'check_in_date' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'device_type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
    'status' => array('type' => 'string', 'null' => false, 'length'=>200, 'default' => 'new', 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

  public $checkout_reservation = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'request_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
    'device_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
    'saved_device_location' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $commands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'parameters' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $computer = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'DeviceType' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'EnableMonitoring' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'ComputerName' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'SerialNumber' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'AssetId' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'unsigned' => false),
		'CurrentUser' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'ComputerLocation' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Manufacturer' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Model' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'OS' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Memory' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'unsigned' => false),
		'MemoryFree' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'CPU' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'NumberOfMonitors' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'IPaddress' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'IPv6address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'MACaddress' => array('type' => 'string', 'null' => false, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'DiskSpace' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'unsigned' => false),
		'DiskSpaceFree' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'unsigned' => false),
		'LastUpdated' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'LastBooted' => array('type' => 'timestamp', 'null' => false, 'default' => '1979-12-31 18:00:00'),
		'SupplicantUsername' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'SupplicantPassword' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'ApplicationUpdates' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'WipedHD' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Recycled' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'RedeployedAs' => array('type' => 'string', 'null' => false, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'notes' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'WindowsIndex' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2', 'unsigned' => false),
		'IsAlive' => array('type' => 'string', 'null' => false, 'default' => 'true', 'length' => 6, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
    'CanCheckout' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
    'IsCheckedOut' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'ComputerName' => array('column' => 'ComputerName', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'MyISAM')
	);

	public $computer_logins = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'Username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'LoginDate' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $decommissioned = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'ComputerName' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'SerialNumber' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'AssetId' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'CurrentUser' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Location' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Manufacturer' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'CPU' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'NumberOfMonitors' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'IPaddress' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'MACaddress' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'OS' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Memory' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false),
		'LastUpdated' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'WipedHD' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'Recycled' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'RedeployedAs' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'notes' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'MyISAM')
	);

	public $device_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'icon' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'attributes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'check_running' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'allow_decom' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'exclude_ad_sync' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $disk = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'label' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'total_space' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false),
		'space_free' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'Local', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $email_queue = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
    'recipient' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $license_keys = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'ProgramName' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'KeyCode' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $lifecycles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'application_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'update_frequency' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'last_check' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'notes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $location = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'location' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'is_default' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'MyISAM')
	);

	public $logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'DATED' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'LOGGER' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'LEVEL' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'MESSAGE' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'MyISAM')
	);

	public $operating_systems = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'eol_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'InnoDB')
	);

	public $programs = array(
		'ID' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'program' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'version' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8mb3_general_ci', 'charset' => 'utf8mb3'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'ID', 'unique' => 1),
			'comp_id' => array('column' => 'comp_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8mb3', 'collate' => 'utf8mb3_general_ci', 'engine' => 'MyISAM')
	);

	public $restricted_programs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $schedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'schedule' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'parameters' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 75, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'startmode' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'MyISAM')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'gravatar' => array('type' => 'string', 'null' => true, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'send_email' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

}
