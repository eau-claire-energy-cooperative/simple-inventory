
<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/inventory/moreInfo/' . $device['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="mdi mdi-chevron-left icon-sm icon-inline text-white-50"></i> Back </a>
</div>
<?php if(isset($errors)): ?>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Warning</h6>
        </div>
        <div class="card-body">
          <p><?= $errors ?></p>
        </div>
    </div>
  </div>
</div>
<?php else: ?>
<?php $options=array('Yes' => 'Yes', 'No' => 'No'); ?>
<?= $this->Form->create(null, ['url'=>'/inventory/confirmDecommission/' . $device['id']]) ?>
<?= $this->Form->input('id', ['type' => 'hidden', 'value'=>$device['id']]);?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <p>Please confirm the following before decommissioning the device. Once confirmed the device will be removed from inventory and stored as a decommissioned device.</p>
        <div class="row mb-2">
          <div class="col-sm-4">Did you wipe the hard drive?</div>
          <div class="col-sm-8"><?= $this->Form->select('WipedHD', $options, ['class'=>'custom-select','multiple'=>false,'value'=>'Yes']) ?></div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4">Was the device recycled?</div>
          <div class="col-sm-8"><?= $this->Form->select('Recycled', $options, ['class'=>'custom-select','multiple'=>false,'value'=>'Yes']) ?></div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4">Device Redeployed as: </div>
          <div class="col-sm-8"><?= $this->Form->input('RedeployedAs', array('class'=>'form-control')) ?></div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-4">Notes</div>
          <div class="col-sm-8"><?= $this->Form->textarea('notes', array('rows' => '3','value'=>$device['notes'], 'class'=>'form-control')) ?></div>
        </div>
        <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-8"><?= $this->Form->Submit('Confirm', ['class'=>'btn btn-primary btn-block']) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->Form->end(); ?>
<?php endif ?>
