<?php 
App::uses('Location','Model');
App::uses('User','Model');
App::uses('Command','Model');
App::uses('Setting','Model');
App::uses('ClassRegistry','Utility');

//USE: Console/cake schema generate|update|create

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
					$location->saveMany(array(array('Location'=>array('location'=>'IT','is_default'=>false)),
											  array('Location'=>array('location'=>'Human Resources','is_default'=>false)),
											  array('Location'=>array('location'=>'Office','is_default'=>true)),
											  array('Location'=>array('location'=>'Operations','is_default'=>false))));			
					
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
					$settings->saveMany(array(array('Setting'=>array('Setting.key'=>'smtp_server','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'smtp_user','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'smtp_pass','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'smtp_auth','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'outgoing_email','Setting.value'=>'admin@domain.com')),
											  array('Setting'=>array('Setting.key'=>'computer_ignore_list','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'auth_type','Setting.value'=>'local')),
											  array('Setting'=>array('Setting.key'=>'ldap_host','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'ldap_port','Setting.value'=>'389')),
											  array('Setting'=>array('Setting.key'=>'ldap_basedn','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'ldap_user','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'ldap_password','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'ldap_computers_basedn','Setting.value'=>'')),
											  array('Setting'=>array('Setting.key'=>'show_computer_commands','Setting.value'=>'true')),
											  array('Setting'=>array('Setting.key'=>'domain_username','Setting.value'=>'administrator')),
											  array('Setting'=>array('Setting.key'=>'domain_password','Setting.value'=>'password')),
											  array('Setting'=>array('Setting.key'=>'shutdown_message','Setting.value'=>'The Administrator has initiated a shutdown of your PC')),
											  array('Setting'=>array('Setting.key'=>'computer_auto_add','Setting.value'=>'false'))));
					break;
				case 'commands':
					//create some of the default commands
					$command = ClassRegistry::init('Command');
					$command->create();
					$command->saveMany(array(array('Command'=>array('name'=>'Restricted Programs','parameters'=>'')),
											array('Command'=>array('name'=>'Wake Computer','parameters'=>'Computer Name')),
											array('Command'=>array('name'=>'Shutdown/Restart Computer','parameters'=>'Computer Name, Restart')),
											array('Command'=>array('name'=>'Send Emails','parameters'=>'')),
											array('Command'=>array('name'=>'Check Disk space','parameters'=>'Minimum Space Threshold'))));
					break;
			}	
		}
		
	}

	public $commands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'parameters' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $computer = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'EnableMonitoring' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ComputerName' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'SerialNumber' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'AssetId' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 255),
		'CurrentUser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ComputerLocation' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'OS' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Memory' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 15),
		'MemoryFree' => array('type' => 'float', 'null' => false, 'default' => NULL),
		'CPU' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'NumberOfMonitors' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'IPaddress' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'MACaddress' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'DiskSpace' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 255),
		'DiskSpaceFree' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20),
		'LastUpdated' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'LastBooted' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'WipedHD' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Recycled' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'RedeployedAs' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'notes' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'WindowsIndex' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2'),
		'IsAlive' => array('type' => 'string', 'null' => false, 'default' => 'true', 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $computer_logins = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'Username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'LoginDate' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $decommissioned = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'ComputerName' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'SerialNumber' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'AssetId' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 255),
		'CurrentUser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Location' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'CPU' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'NumberOfMonitors' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'IPaddress' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'MACaddress' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'OS' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Memory' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 255),
		'LastUpdated' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'WipedHD' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'Recycled' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'RedeployedAs' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'notes' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $disk = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'label' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'total_space' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20),
		'space_free' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $email_queue = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'message' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $location = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'location' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_default' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $programs = array(
		'ID' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'version' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'ID', 'unique' => 1), 'comp_id' => array('column' => 'comp_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $restricted_programs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $schedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'schedule' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'parameters' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'comp_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'startmode' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	public $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'send_email' => array('type' => 'string', 'null' => false, 'default' => 'false', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
