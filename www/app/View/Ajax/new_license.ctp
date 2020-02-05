<h2>New License</h2>
<?php echo $this->Form->create('License',array('url'=>'/admin/licenses')) ?>
<table>
	<tr>
		<td><h3>Program Name: </h3></td>
		<td><?php echo $this->Form->input('ProgramName', array('div'=>false, label=>false)) ?></td>
	</tr>
	<tr>
		<td><h3>License Assigned To: </h3></td>
		<td><?php echo $this->Form->select('comp_id', $computers, array('label'=>false, 'empty'=>false, 'value'=>$current_comp)) ?></td>
	</tr>
	<tr>
		<td><h3>License Key: </h3></td>
		<td><?php echo $this->Form->input('LicenseKey', array('label'=>false, 'empty'=>false)) ?></td>
	</tr>
</table>

<div align="right"><?php echo $this->Form->end('Add') ?></div>