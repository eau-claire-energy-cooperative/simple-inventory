<h1 class="h3 mb-2 text-gray-800">New License</h1>
<?php echo $this->Form->create('License',array('url'=>'/manage/licenses')) ?>
<div class="row">
	<div class="col-sm-4">Program Name:</div>
	<div class="col-sm-8"><?php echo $this->Form->input('ProgramName', array('div'=>false, label=>false, 'class'=>'form-control')) ?></div>
</div>
<div class="row mt-1">
  <div class="col-sm-4">License Assigned To: </div>
  <div class="col-sm-8"><?php echo $this->Form->select('comp_id', $computers, array('label'=>false, 'empty'=>false, 'value'=>$current_comp, 'class'=>'custom-select')) ?></div>
</div>
<div class="row mt-1">
  <div class="col-sm-4">License Key: </div>
  <div class="col-sm-8"><?php echo $this->Form->input('LicenseKey', array('div'=>false, label=>false, 'class'=>'form-control')) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>