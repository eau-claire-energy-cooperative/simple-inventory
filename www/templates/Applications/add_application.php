<?= $this->Form->create(null, ['url'=>'/applications/']);?>

<p>Create an application manually to be added to the system. You can assign it to devices using the "Assign To" button on the <a href="index">Applications</a> page.</p>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Application Name: </div>
            <div class="col-md-8"><?= $this->Form->input('name', ['class'=>'form-control', 'type'=>'text']); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Version: </div>
            <div class="col-md-8"><?= $this->Form->input('version', ['class'=>'form-control', 'type'=>'text']); ?></div>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?= $this->Form->Submit('Save',['class'=>'btn btn-primary btn-block']) ?></div>
          </div>
        </div>
    </div>
  </div>
</div>
<?= $this->Form->end(); ?>
