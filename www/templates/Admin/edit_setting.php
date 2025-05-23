
<?= $this->Form->create($setting);?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <?= $this->Form->hidden('id'); ?>
          <div class="row mb-1">
            <div class="col-md-4">Setting Key: </div>
            <div class="col-md-8"><?= $this->Form->input('Setting.key', ['class'=>'form-control',"label"=>false]); ?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Setting Value: </div>
            <div class="col-md-8"><?= $this->Form->input('Setting.value', ['class'=>'form-control',"label"=>false]); ?></div>
          </div>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
          </div>
          <?= $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
