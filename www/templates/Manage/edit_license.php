<?= $this->Form->create($license, ['url' => '/manage/edit_license/' . $license['id']]); ?>
<?= $this->Form->input('id', ['type' => 'hidden']);?>

<div class="mb-4" align="right">
  <?= $this->Form->Submit('Save', ['class'=>'d-none d-sm-inline-block btn btn-primary shadow-sm mr-2']) ?>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">License Information</h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">License Name</div>
            <div class="col-md-8"><?= $this->Form->input('LicenseName', ['class'=>'form-control']);?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Vendor</div>
              <div class="col-md-8"><?= $this->Form->input('Vendor', ['class'=>'form-control']);?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Expiration Date</div>
              <?php $exp_date = ($license->ExpirationDate != null)? $license->ExpirationDate->format('Y-m-d') : ''; ?>
              <div class="col-md-6"><?= $this->Form->input('ExpirationDate', ["value"=>$exp_date, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date']); ?></div>
              <div class="col-md-2 align-self-end">

                <?= $this->Form->checkbox('NoExpiration', ['class'=>'form-check-input', 'checked'=>empty($exp_date)]) ?>
                <label>No Expiration</label>
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Renewal Reminders</div>
              <div class="col-md-2"><?= $this->Form->input('StartReminder', ['class'=>'form-control', 'default'=>0]); ?></div>
              <div class="col-md-6 align-self-end">how many months before the expiration to start sending reminders</div>
          </div>
        </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?= $this->Form->textarea('Notes', array('label'=>false,'class'=>'form-control')); ?>
      </div>
    </div>
  </div>
</div>

<?= $this->Form->end();?>
