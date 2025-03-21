<?php
	$parameters = array();
	if($command['parameters'] != '')
	{
		$parameters = explode(',', $command['parameters']);
	}
?>

<h1 class="h3 mb-2 text-gray-800">Setup Command '<?= $command['name']; ?>'</h1>
<p><?= $command['description'] ?></p>
<?= $this->Form->create(null,  ['url'=>'/manage/schedule']) ?>
<?= $this->Form->hidden('command_id', ['value'=>$command['id']]); ?>
<?= $this->Form->hidden('parameter_list', ['value'=>$command['parameters']]); ?>
<div class="row mb-2">
  <div class="col-sm-4">Schedule: </div>
  <div class="col-sm-8"><?= $this->Form->input('schedule', ['class'=>'form-control','value'=>'0 0 1 * *']) ?></div>
</div>
<?php if(count($parameters) != 0): ?>
  <?php foreach($parameters as $param): ?>
<div class="row">
  <div class="col-sm-4"><?= $param ?>:</div>
  <div class="col-sm-8"><?= $this->Form->input('param_' . $param, ['class'=>'form-control']) ?></div>
</div>
  <?php endforeach; ?>
<?php endif; ?>

<div class="row">
  <div class="col-sm-8"></div>
  <div class="col-sm-4"><?= $this->Form->submit('Create', ['class'=>'btn btn-primary btn-user btn-block mt-2']) ?></div>
</div>

<?= $this->Form->end() ?>
