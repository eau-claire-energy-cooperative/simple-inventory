<?= $this->Form->create(null);?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">Location Name: </div>
            <div class="col-md-8"><?= $this->Form->input('location',['class'=>'form-control']); ?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Auto Location Regex: </div>
            <div class="col-md-8">
              <?= $this->Form->input('auto_regex',['class'=>'form-control']); ?>
              <a href="https://en.wikipedia.org/wiki/Regular_expression">Regular expression</a> that will put devices in this location automatically upon creation via the API based on the device name.
            </div>
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
