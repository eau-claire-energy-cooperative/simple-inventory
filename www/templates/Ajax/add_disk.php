<div class="container" style="width:65%">
  <h1 class="h3 mb-2 text-gray-800">Add Disk</h1>
  <?= $this->Form->create(null, ['url'=>'/inventory/add_disk']) ?>
  <?= $this->Form->hidden('comp_id', ['value'=>$comp_id]) ?>
  <?= $this->Form->hidden('type', ['value'=>'Local']) ?>
  <div class="row">
  	<div class="col-sm-4">Label:</div>
  	<div class="col-sm-8"><?= $this->Form->input('label', ['class'=>'form-control']) ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">Total Space (in KB):</div>
    <div class="col-sm-8"><?= $this->Form->input('total_space', ['class'=>'form-control']) ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">Free Space (in KB): </div>
    <div class="col-sm-8"><?= $this->Form->input('space_free', ['class'=>'form-control']) ?></div>
  </div>
  <div class="row mt-2">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
  </div>
  <?= $this->Form->end() ?>
</div>
