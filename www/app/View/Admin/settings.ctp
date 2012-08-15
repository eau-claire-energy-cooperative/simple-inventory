<p><?php echo $this->Html->link('Home', '/'); ?> | 
<?php echo $this->Html->link('Add Setting','/admin/edit_setting') ?></p>
<p>Please note, you should not add or remove settings from this page unless you know what you are doing. They will affect, and possibly break, how the website and inventory update functions run. </p>
<table>
	<?php foreach($settings as $aSetting): ?>
	<tr>
		<td><?php echo $aSetting['Setting']['key'] ?></td>
		<td><?php echo $aSetting['Setting']['value'] ?></td>
		<td><?php echo $this->Html->link("Edit","/admin/edit_setting/". $aSetting['Setting']['id']) ?>  <?php echo $this->Html->link("Delete","/admin/settings/delete?id=". $aSetting['Setting']['id']) ?></td>
	</tr>
	<?php endforeach; ?>
</table>