<h1 class="h3 mb-2 text-gray-800">Assign Application - <?php echo $application['name'] ?></h1>
<?php echo $this->Form->create('ApplicationInstall',array('url'=>'/applications/assign_application')) ?>
<?php echo $this->Form->hidden('application_id',array('value'=>$application['id'])); ?>
<?php echo $this->Form->hidden('application_name',array('value'=>$application['name'])); ?>
<div class="row mt-3">
  <div class="col-sm-4">Assign To: </div>
  <div class="col-sm-8"><?php echo $this->Form->select('comp_id', $computers, array('label'=>false, 'empty'=>false, 'class'=>'custom-select')) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>
