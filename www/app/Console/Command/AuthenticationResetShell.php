<?php 

class AuthenticationResetShell extends AppShell {
	var $uses = array('Setting','User');
	
	public function main(){
		
		$this->out('Resetting Local User Authentication');
		$this->out("");
		
		$this->out('Setting auth type to local');
		$this->Setting->query('update settings set settings.value = "local" where settings.key = "auth_type"');
		
		$this->out('Resetting all user passwords to default');
		$this->Setting->query('update users set password = "1a1dc91c907325c69271ddf0c944bc72"'); //default is hashed value of 'pass'
		
		$this->out('If you still have problems logging in set your settings encryption value back to false if set to true');
		$this->dblog('Authentication Reset command run, reset all local user logins', 'CLI', 'WARNING');
		
	}
	
}
?>