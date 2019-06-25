<?php echo $this->Html->link('Admin', array('action'=>"index")); ?>
<?php echo $this->Form->create('Location', array('url' => '/admin/editLocation')); ?>
 
<table>
		
	<tr>
		<td><?php echo $this->Form->input('location',array('label'=>'Location Name'));?></td>
	</tr>
	
</table>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<?php echo $this->Form->end('Update');?>
