<div class="card shadow mb-4">
    <div class="card-body">
      <p class="mb-2">Fill in the information below to manually add a new device to the inventory system.</p>
      <?= $this->Form->create(null);?>
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Device Type: </label>
        <div class="col-md-6"><?= $this->Form->input('DeviceType', ['class'=>'custom-select', 'type' => 'select', 'id' => 'device_id', 'options' => $device_types]); ?></div>
      </div>
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Device Name: </label>
        <div class="col-md-6"><?= $this->Form->input('ComputerName', array('div'=>false, 'label'=>false, 'class'=>'form-control')); ?></div>
      </div>
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Location: </label>
        <div class="col-md-6"><?= $this->Form->input('ComputerLocation', ['class'=>'custom-select', 'type' => 'select', 'id' => 'location_id', 'options' => $location]); ?></div>
      </div>
      <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-6"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
        <div class="col-md-2"></div>
      </div>
      <?= $this->Form->end(); ?>
  </div>
</div>
