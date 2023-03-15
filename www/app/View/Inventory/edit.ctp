<?php echo $this->Form->create('Computer', array('url' => '/Inventory/edit')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<div class="row">
  <div class="col-md-6">
    <h2 class="text-gray-400"><i class="mdi mdi-<?php echo $this->request->data['DeviceType']['icon'] ?> icon-2x icon-inline"></i> <?php echo $this->request->data['DeviceType']['name'] ?></h2>
  </div>
  <div class="col-md-6 mb-4" align="right">
    <?php echo $this->Form->Submit('Update',array('class'=>'d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2')) ?>
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
            <div class="col-md-8"><?php echo $this->Form->input('ComputerName', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Current Location</div>
              <div class="col-md-8"><?php echo $this->Form->input('ComputerLocation',array('class'=>'custom-select','label'=>false,'type' => 'select', 'id' => 'location_id', 'options' => $location)); ?></div>
          </div>
          <?php foreach(array_keys($generalAttributes) as $a): ?>
          <?php if(in_array($a, $allowedAttributes)): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?php echo $generalAttributes[$a] ?></div>
            <div class="col-md-8"><?php echo $this->Form->input($a, array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <?php endif ?>
          <?php endforeach; ?>
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
            <div class="col-md-4"><?php echo $hardwareAttributes[$a] ?></div>
            <div class="col-md-8"><?php echo $this->Form->input($a, array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <?php endif ?>
          <?php endforeach; ?>
          <?php if(in_array('DriveSpace', $allowedAttributes)): ?>
          <div class="row mb-1">
            <div class="col-md-4"><?php echo $hardwareAttributes['DriveSpace'] ?></div>
            <div class="col-md-8">
              <div align="right">
                <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/add_disk/' . $this->request->data['Computer']['id']) ?>" class="btn btn-primary btn-sm mt-1"><i class="mdi mdi-plus icon-sm icon-inline"></i> Add Drive</a>
              </div>
              <div class="ml-3">
                <?php echo $this->AttributeDisplay->displayAttribute('DriveSpace', $this->request->data, true) ?>
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
            <div class="col-md-4"><?php echo $networkAttributes[$a] ?></div>
            <div class="col-md-8"><?php echo $this->Form->input($a, array('label'=>false,'class'=>'form-control'));?></div>
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
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php echo $this->Form->input('notes', array('label'=>false,'class'=>'form-control')); ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->Form->end();?>
