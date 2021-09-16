<p class="mb-2">Fill in the information below to manually add a new device to the inventory system.</p>
<?php echo $this->Form->create('Computer');?>
<div class="form-group row">
  <label class="col-md-2 col-form-label">Device Type: </label>
  <div class="col-md-6"><?php echo $this->Form->input('DeviceType',array('class'=>'custom-select', 'type' => 'select', 'id' => 'device_id', 'options' => $device_types, 'div'=>false, 'label'=>false)); ?></div>
</div>
<div class="form-group row">
  <label class="col-md-2 col-form-label">Device Name: </label>
  <div class="col-md-6"><?php echo $this->Form->input('ComputerName', array('div'=>false, 'label'=>false, 'class'=>'form-control')); ?></div>
</div>
<div class="form-group row">
  <label class="col-md-2 col-form-label">Asset ID: </label>
  <div class="col-md-6"><?php echo $this->Form->input('AssetId', array('div'=>false, 'label'=>false, 'class'=>'form-control')); ?></div>
</div>
<div class="form-group row">
  <label class="col-md-2 col-form-label">Location: </label>
  <div class="col-md-6"><?php echo $this->Form->input('ComputerLocation',array('class'=>'custom-select', 'type' => 'select', 'id' => 'location_id', 'options' => $location, 'div'=>false, 'label'=>false)); ?></div>
</div>
<div class="row mt-4">
  <div class="col-md-2"></div>
  <div class="col-md-4"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
  <div class="col-md-4"></div>
</div>
<?php echo $this->Form->end(); ?>
