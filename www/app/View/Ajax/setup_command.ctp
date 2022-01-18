<?php
	$parameters = array();
	if($command['Command']['parameters'] != '')
	{
		$parameters = explode(',',$command['Command']['parameters']);
	}
?>

<h1 class="h3 mb-2 text-gray-800">Setup Command '<?php echo $command['Command']['name']; ?>'</h1>
<p><?php echo $command['Command']['description'] ?></p>
<?php echo $this->Form->create('Schedule',array('url'=>'/manage/schedule')) ?>
<?php echo $this->Form->hidden('command_id',array('value'=>$command['Command']['id'])); ?>
<?php echo $this->Form->hidden('parameter_list',array('value'=>$command['Command']['parameters'])); ?>
<div class="row mb-2">
  <div class="col-sm-4">Schedule: </div>
  <div class="col-sm-8"><?php echo $this->Form->input('schedule',array('label'=>false,'div'=>false,'class'=>'form-control','value'=>'0 0 1 * *')) ?></div>
</div>
<?php
    if(count($parameters) != 0):
      foreach($parameters as $param): ?>
<div class="row">
  <div class="col-sm-4"><?php echo $param ?>:</div>
  <div class="col-sm-8"><?php echo $this->Form->input('param_' . $param,array('label'=>false, 'div'=>false, 'class'=>'form-control')) ?></div>
</div>
<?php endforeach;
    endif; ?>


<div class="row">
  <div class="col-sm-8"></div>
  <div class="col-sm-4"><?php echo $this->Form->submit('Create',array('class'=>'btn btn-primary btn-user btn-block mt-2')) ?></div>
</div>

<?php echo $this->Form->end() ?>
