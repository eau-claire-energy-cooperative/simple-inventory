<?= $this->Form->create($computer, array('url' => '/inventory/edit')); ?>
<?= $this->Form->input('id', array('type' => 'hidden'));?>
<div class="row">
  <div class="col-md-6">
    <h2 class="text-gray-400"><i class="mdi mdi-<?= $computer['device_type']['icon'] ?> icon-2x icon-inline"></i> <?= $computer['device_type']['name'] ?></h2>
  </div>
  <div class="col-md-6 mb-4" align="right">
    <?= $this->Form->Submit('Update',array('class'=>'d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2')) ?>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">Device Name</div>
            <div class="col-md-8"><?= $this->Form->input('ComputerName', ['class'=>'form-control']);?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Current Location</div>
              <div class="col-md-8"><?= $this->Form->input('ComputerLocation', ['class'=>'custom-select','type' => 'select', 'id' => 'location_id', 'options' => $location]); ?></div>
          </div>
          <?php foreach(array_keys($generalAttributes) as $a): ?>
          <?php if(in_array($a, $allowedAttributes)): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?= $generalAttributes[$a] ?></div>
            <div class="col-md-8"><?= $this->Form->control($a,  ['class'=>'form-control', 'label'=>false]); ?></div>
          </div>
          <?php endif ?>
          <?php endforeach; ?>
          <?php if($settings['enable_device_checkout'] == 'true'): ?>
          <div class="row mb-1">
              <div class="col-md-4">Available For Checkout</div>
              <div class="col-md-8"><?= $this->Form->input('CanCheckout', ['class'=>'custom-select', 'type' => 'select', 'options' => ['true'=>'Yes', 'false'=>'No']]); ?></div>
          </div>
          <?php endif ?>
        </div>
    </div>
  </div>
</div>

<?php if(count(array_intersect(array_keys($hardwareAttributes), $allowedAttributes)) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Hardware Information</h6>
        </div>
        <div class="card-body">
          <?php foreach(array_keys($hardwareAttributes) as $a): ?>
          <?php if(in_array($a, $allowedAttributes) && $a != 'DriveSpace'): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?= $hardwareAttributes[$a] ?></div>
            <div class="col-md-8"><?= $this->Form->control($a,  ['class'=>'form-control', 'label'=>false]); ?></div>
          </div>
          <?php endif ?>
          <?php endforeach; ?>
          <?php if(in_array('DriveSpace', $allowedAttributes)): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?= $hardwareAttributes['DriveSpace'] ?></div>
            <div class="col-md-8">
              <div align="right">
                <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/add_disk/' . $computer['id']) ?>" class="btn btn-primary btn-sm mt-1"><i class="mdi mdi-plus icon-sm icon-inline"></i> Add Drive</a>
              </div>
              <div class="ml-3">
                <?= $this->AttributeDisplay->displayAttribute('DriveSpace', $computer, true) ?>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(count(array_intersect(array_keys($networkAttributes), $allowedAttributes)) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-info shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Network Information</h6>
        </div>
        <div class="card-body">
          <?php foreach(array_keys($networkAttributes) as $a): ?>
          <?php if(in_array($a, $allowedAttributes)): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?= $networkAttributes[$a] ?></div>
            <div class="col-md-8"><?= $this->Form->control($a,  ['class'=>'form-control', 'label'=>false]); ?></div>
          </div>
          <?php endif ?>
          <?php endforeach; ?>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <div class="col-xl-8">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?= $this->Form->textarea('notes', ['class'=>'form-control']); ?>
      </div>
    </div>
  </div>
</div>
<?= $this->Form->end();?>
