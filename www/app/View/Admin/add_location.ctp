<?php echo $this->Html->link('Admin', array('action'=>"index")); ?> 
<?php echo $this->Form->create('Location');?>
<table>
	<tr>
		<td><?php echo $this->Form->input('location',array("label"=>'Tag Name')); ?></td>
	</tr>
</table>
<?php echo $this->Form->end('Save'); ?>

