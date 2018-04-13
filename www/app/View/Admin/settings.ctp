<p><?php echo $this->Html->link('Admin', array('action'=>"index")); ?> </p>

<?php echo $this->Form->create('Setting',array('url'=>'/admin/settings')) ?>
<div align="right">
	<?php echo $this->Form->Submit('Update') ?>
</div>
<h2>General</h2>
<table>
	<tr>
		<td width="50%">Authentication Method</td>
		<td><?php echo $this->Form->select('auth_type',array('local'=>'Local Users','ldap'=>'LDAP Connection'),array('value'=>$settings['auth_type'],'empty'=>false)) ?></td>
	</tr>
	<tr>
		<td width="50%">Computer Ignore List (comma separated)</td>
		<td><?php echo $this->Form->input('computer_ignore_list',array('label'=>false,'value'=>$settings['computer_ignore_list'])) ?></td>
	</tr>
	<tr>
		<td>Enable Computer Auto Add</td>
		<td><?php echo $this->Form->select('computer_auto_add',array('true'=>'Yes','false'=>'No'),array('value'=>$settings['computer_auto_add'],'empty'=>false)) ?></td>
	</tr>
</table>

<h2>Computer Info Page</h2>
<table>
	<tr>
		<td width="50%">Show Computer Commands</td>
		<td><?php echo $this->Form->select('show_computer_commands',array('true'=>'Yes','false'=>'No'),array('value'=>$settings['show_computer_commands'],'empty'=>false)) ?></td>
	</tr>
	<tr>
		<td>Shutdown Computer Message</td>
		<td><?php echo $this->Form->input('shutdown_message',array('label'=>false,'value'=>$settings['shutdown_message'])) ?></td>
	</tr>
	<tr>
		<td>Admin Account (Domain account works best)</td>
		<td><?php echo $this->Form->input('domain_username',array('label'=>false,'value'=>$settings['domain_username'])) ?></td>
	</tr>
	<tr>
		<td>Admin Password</td>
		<td><?php echo $this->Form->input('domain_password',array('label'=>false,'value'=>$settings['domain_password'])) ?></td>
	</tr>
	<tr>
		<td>Fields To Display</td>
		<?php
			//convert string to array
			$displayAttributes = explode(',',$settings['display_attributes']);
		?>
		<td><?php echo $this->Form->select('display_attributes',array("ComputerName"=>"Computer Name","Tag"=>"Tag","CurrentUser"=>"Current User","SerialNumber"=>"Serial Number","AssetId"=>"Asset ID","Model"=>"Model","OS"=>"Operating System","CPU"=>"CPU","Memory"=>"Memory","NumberOfMonitors"=>"Number of Monitors","IPAddress"=>"IP Address","MACAddress"=>"MAC Address","DriveSpace"=>"Drive Space","LastUpdated"=>"Last Updated","Status"=>"Status"),array('multiple'=>true,'label'=>false,'value'=>$displayAttributes)) ?></td>
	</tr>
</table>

<h2>LDAP Settings</h2>
<table>
	<tr>
		<td width="50%">LDAP Host</td>
		<td><?php echo $this->Form->input('ldap_host',array('label'=>false,'value'=>$settings['ldap_host'])) ?></td>
	</tr>
	<tr>
		<td>LDAP Port</td>
		<td><?php echo $this->Form->input('ldap_port',array('label'=>false,'value'=>$settings['ldap_port'])) ?></td>
	</tr>
	<tr>
		<td>LDAP Authentication Search Base</td>
		<td><?php echo $this->Form->input('ldap_basedn',array('label'=>false,'value'=>$settings['ldap_basedn'])) ?></td>
	</tr>
	<tr>
		<td>LDAP Computers Search Base</td>
		<td><?php echo $this->Form->input('ldap_computers_basedn',array('label'=>false,'value'=>$settings['ldap_computers_basedn'])) ?></td>
	</tr>
	<tr>
		<td>LDAP Username</td>
		<td><?php echo $this->Form->input('ldap_user',array('label'=>false,'value'=>$settings['ldap_user'])) ?></td>
	</tr>
	<tr>
		<td>LDAP Password</td>
		<td><?php echo $this->Form->input('ldap_password',array('label'=>false,'value'=>$settings['ldap_password'])) ?></td>
	</tr>
</table>

<h2>Mail Settings</h2>
<table>
	<tr>
		<td width="50%">Send Mail As</td>
		<td><?php echo $this->Form->input('outgoing_email',array('label'=>false,'value'=>$settings['outgoing_email'])) ?></td>
	</tr>
	<tr>
		<td>SMTP Host</td>
		<td><?php echo $this->Form->input('smtp_server',array('label'=>false,'value'=>$settings['smtp_server'])) ?></td>
	</tr>
	<tr>
		<td>SMTP Authentication</td>
		<td><?php echo $this->Form->select('smtp_auth',array('true'=>'Yes','false'=>'No'),array('value'=>$settings['smtp_auth'],'empty'=>false)) ?></td>
	</tr>
	<tr>
		<td>SMTP Username</td>
		<td><?php echo $this->Form->input('smtp_user',array('label'=>false,'value'=>$settings['smtp_user'])) ?></td>
	</tr>
	<tr>
		<td>SMTP Password</td>
		<td><?php echo $this->Form->input('smtp_pass',array('label'=>false,'value'=>$settings['smtp_pass'])) ?></td>
	</tr>
</table>

<div align="right">
	<?php echo $this->Form->Submit('Update') ?>
</div>
