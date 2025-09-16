<h1 class="h3 mb-2 text-gray-800">Edit License Key</h1>
<?= $this->Form->create(null, ['url'=>'/manage/view_license/' . $license_id]) ?>
<?= $this->Form->hidden('license_key_id', ['value'=>$license_key_id]); ?>
<?= $this->Form->hidden('license_action', ['value'=>'edit']); ?>
<div class="row mb-2">
  <div class="col-sm-4">License: </div>
  <div class="col-sm-8">
    <?= $license_key['license']['LicenseName'] ?><br />
    <?= $license_key['Keycode'] ?>
  </div>
</div>
<div class="row mt-2">
  <div class="col-sm-4">Quantity</div>
  <div class="col-sm-8"><?= $this->Form->control('Quantity', ['type'=>'number', 'label'=>false, 'empty'=>false, 'value'=>$license_key['Quantity'], 'class'=>'form-control']) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
</div>
<?= $this->Form->end() ?>
