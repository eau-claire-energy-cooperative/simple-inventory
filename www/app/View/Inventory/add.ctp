<?php echo $this->Html->link('Home', array('action' => 'home')); ?>
<?php echo $this->Form->create('Computer');?>
<table width="70%">
	<tr>
		<td><?php echo $this->Form->input('ComputerName'); ?></td>
		<td><?php echo $this->Form->input('AssetId'); ?></td>
		<td><?php echo $this->Form->input('ComputerLocation',array('type' => 'select', 'id' => 'location_id', 'options' => $location)); ?></td>
	</tr>
</table>
<?php echo $this->Form->end('Save'); ?>