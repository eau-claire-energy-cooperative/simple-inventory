<?php
	$parameters = array();
	if($command['Command']['parameters'] != '')
	{
		$parameters = explode(',',$command['Command']['parameters']);
	}
?>

<h2>Setup Command '<?php echo $command['Command']['name']; ?>'</h2>
<p><?php echo $command['Command']['description'] ?></p>
<?php echo $this->Form->create('Schedule',array('url'=>'/admin/schedule')) ?>
<?php echo $this->Form->hidden('command_id',array('value'=>$command['Command']['id'])); ?>
<?php echo $this->Form->hidden('parameter_list',array('value'=>$command['Command']['parameters'])); ?>
<table>
	<tr>
		<td><h3>Schedule: </h3></td>
		<td><?php echo $this->Form->input('schedule',array('label'=>false)) ?></td>
	</tr>
	<?php
		if(count($parameters) != 0): 
			foreach($parameters as $param): ?>
	<tr>
		<td><h3><?php echo $param ?>:</h3></td>
		<td><?php echo $this->Form->input('param_' . $param,array('label'=>false)) ?></td>
	</tr>
	<?php endforeach;
		endif; ?>
</table>

<div align="right"><?php echo $this->Form->end('Create') ?></div>
