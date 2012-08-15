<?php echo $this->Html->link('Home', '/'); ?> 

<?php echo $this->Form->create('Setting');?>
<table>
	<tr>
		<?php if(isset($setting)): ?>
		<td><?php echo $this->Form->input('key',array("label"=>'Setting key','value'=>$setting['Setting']['key'])); ?></td>
		<td><?php echo $this->Form->input('value',array("label"=>'Setting value','value'=>$setting['Setting']['value'])); ?>
			<?php echo $this->Form->hidden('id',array('value'=>$setting['Setting']['id'])); ?>
		</td>
		<?php else: ?>
		<td><?php echo $this->Form->input('key',array("label"=>'Setting key')); ?></td>
		<td><?php echo $this->Form->input('value',array("label"=>'Setting value')); ?></td>
		<?php endif; ?>
	</tr>
</table>
<?php echo $this->Form->end('Save'); ?>