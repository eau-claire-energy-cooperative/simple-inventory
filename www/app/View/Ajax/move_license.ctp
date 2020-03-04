<h1 class="h3 mb-2 text-gray-800">Move License</h1>
<?php echo $this->Form->create('MoveLicense',array('url'=>'/manage/licenses')) ?>
<?php echo $this->Form->hidden('license_id',array('value'=>$license_id)); ?>
<div class="row">
  <div class="col-sm-4">License Assigned To: </div>
  <div class="col-sm-8"><?php echo $this->Form->select('computer', $computers, array('label'=>false, 'empty'=>false, 'value'=>$current_comp, 'class'=>'custom-select')) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>