<?php echo $this->Html->link('Home', '/'); ?>
<?php echo $this->Form->create('Location');?>
<table>
	<tr>
		<td><?php echo $this->Form->input('location',array("label"=>'Location Name')); ?></td>
	</tr>
</table>
<?php echo $this->Form->end('Save'); ?>

