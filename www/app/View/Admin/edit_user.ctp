<?php echo $this->Html->link('Home', '/'); ?>
<?php echo $this->Form->create('User', array('url' => '/admin/editUser')); ?>
 
<table>
		
	<tr>
		<td><?php echo $this->Form->input('name');?></td>
		<td><?php echo $this->Form->input('username');?></td>
		<td><?php echo $this->Form->input('password');?></td>
	</tr>
	
</table>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<?php echo $this->Form->end('Update');?>
