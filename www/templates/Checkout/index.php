<?php if(!$authenticated):?>
<div class="row mb-2 p-4">
  <div class="col-lg-8">
    <h1>
      <i class="mdi mdi-monitor icon-3x text-gray-900"></i>
      <span class="h4 text-gray-900 mb-4">Device Checkout Request</span>
    </h1>
  </div>
</div>
<?php else: ?>
<div class="card shadow mb-4">
    <div class="card-body">
<?php endif; ?>
<div class="row">
  <?= $this->Form->create(null, array('url'=>'/checkout/'));?>
  <div class="col-lg-12">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <p>Select the type of devices you'd like to checkout and the dates in which you expect to keep them. Requests will be reviewed and approved or denied by the IT department.</p>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col-md-4">Name: </div>
        <div class="col-md-8"><?= $this->Form->input('employee_name', ['class'=>'form-control']); ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-md-4">Email: </div>
        <div class="col-md-8"><?= $this->Form->input('employee_email', ['class'=>'form-control']); ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-sm-4">Check Out Date:</div>
        <div class="col-sm-8">
          <?= $this->Form->input('check_out_date', ['class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y', 'value'=>$checkOut->i18nFormat('yyyy-MM-dd')]); ?>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-sm-4">Check In Date:</div>
        <div class="col-sm-8">
          <!-- two weeks from today -->
          <?php $next_date = date_add(date_create(), date_interval_create_from_date_string("2 weeks")); ?>
          <?= $this->Form->input('check_in_date', ['class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y', 'value'=>$checkIn->i18nFormat('yyyy-MM-dd')]); ?>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-sm-4">Devices:</div>
        <div class="col-sm-8"><?= $this->Form->select('devices',$available, ['class'=>'custom-select','multiple'=>true]) ?><br />
        Hold down the CTL key to select multiple device types.
        </div>
      </div>
      <div class="row">
        <div class="col-md-12"><?= $this->Form->Submit('Submit', ['class'=>'btn btn-primary btn-block']) ?></div>
      </div>
    </div>
  </div>
  <?= $this->Form->end(); ?>
</div>
<?php if($authenticated):?>
  </div>
</div>
<?php endif; ?>
