<div style="min-width: 500px">
  <h1 class="h3 mb-2 text-gray-800">New License Key</h1>
  <?= $this->Form->create(null, ['url'=>'/manage/view_license/' . $license_id]) ?>
  <?= $this->Form->hidden('license_id', ['value'=>$license_id]) ?>
  <div class="row mt-1 mb-2">
    <div class="col-sm-4">License Key: </div>
    <div class="col-sm-8"><?= $this->Form->textarea('Keycode', ['class'=>'form-control', 'rows'=>4]) ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">Quantity: </div>
    <div class="col-sm-8"><?= $this->Form->control('Quantity', ['type'=>'number', 'empty'=>false, 'value'=>1, 'class'=>'form-control']) ?></div>
  </div>
  <div class="row mt-2">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
  </div>
  <?= $this->Form->end() ?>
</div>
