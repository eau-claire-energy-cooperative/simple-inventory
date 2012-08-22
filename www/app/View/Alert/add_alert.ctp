<p><?php echo $this->Html->link('Back','index') ?></p>
?php echo $this->Form->create('Alert');?>
<table>
	<tr>
		<td><?php echo $this->Form->input('location',array("label"=>'Location Name')); ?></td>
	</tr>
</table>
<?php echo $this->Form->end('Save'); ?>