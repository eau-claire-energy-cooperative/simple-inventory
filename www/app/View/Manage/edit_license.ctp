<?php echo $this->Form->create('License', array('url' => '/manage/edit_license')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>

<div class="mb-4" align="right">
  <?php echo $this->Form->Submit('Save',array('class'=>'d-none d-sm-inline-block btn btn-primary shadow-sm mr-2')) ?>
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
            <div class="col-md-8"><?php echo $this->Form->input('LicenseName', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Vendor</div>
              <div class="col-md-8"><?php echo $this->Form->input('Vendor', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Expiration Date</div>
              <div class="col-md-6"><?php echo $this->Form->input('ExpirationDate',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y')); ?></div>
              <div class="col-md-2 align-self-end">
                <?php echo $this->Form->checkbox('NoExpiration', array('label'=>false, 'div'=>false, 'class'=>'form-check-input', 'checked'=>empty($this->data['License']['ExpirationDate']))) ?>
                <label>No Expiration</label>
              </div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Renewal Reminders</div>
              <div class="col-md-2"><?php echo $this->Form->input('StartReminder', array('label'=>false,'class'=>'form-control')); ?></div>
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
        <?php echo $this->Form->input('Notes', array('label'=>false,'class'=>'form-control')); ?>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Form->end();?>
