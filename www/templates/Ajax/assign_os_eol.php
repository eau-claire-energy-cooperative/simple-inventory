<div class="container" style="width:70%">
  <h1 class="h3 mb-2 text-gray-800">Assign End of Life Date</h1>
  <?= $this->Form->create(null, ['url'=>'/applications/operating_systems']) ?>
  <?= $this->Form->hidden('name', ['value'=>$osName]) ?>
  <div class="row">
  	<div class="col-sm-4">Operating System:</div>
  	<div class="col-sm-8"><?= $osName ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">End of Life:</div>
    <div class="col-sm-8"><?= $this->Form->input('eol_date', ['class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y']) ?></div>
  </div>
  <div class="row mt-2">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
  </div>
  <?= $this->Form->end() ?>
</div>
