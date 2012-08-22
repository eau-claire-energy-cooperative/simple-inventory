<p><?php echo $this->Html->link('Home', '/'); ?></p> 

<?php echo $this->Form->create('User',array('url'=>'/admin/editUser')) ?>
<table>
		
	<tr>
		<td><?php echo $this->Form->input('name');?></td>
		<td><?php echo $this->Form->input('username');?></td>
		<td><?php echo $this->Form->input('email') ?></td>
		<td><?php echo $this->Form->input('password');?></td>
	</tr>
	
</table>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<?php if(isset($this->data['User']['id'])) {
	echo $this->Form->input('password_original',array('type'=>'hidden','value'=>$this->data['User']['password']));	
}
?>
<?php echo $this->Form->end('Update');?>
