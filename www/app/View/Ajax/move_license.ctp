<h2>Move License</h2>
<?php echo $this->Form->create('MoveLicense',array('url'=>'/manage/licenses')) ?>
<?php echo $this->Form->hidden('license_id',array('value'=>$license_id)); ?>
<table>
	<tr>
		<td><h3>License Assigned To: </h3></td>
		<td><?php echo $this->Form->select('computer', $computers, array('label'=>false, 'empty'=>false, 'value'=>$current_comp)) ?></td>
	</tr>
</table>

<div align="right"><?php echo $this->Form->end('Move') ?></div>