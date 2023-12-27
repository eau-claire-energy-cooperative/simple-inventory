<!-- Outer Row -->
<div class="row justify-content-center">

  <div class="col-xl-10 col-lg-12 col-md-9">

    <div class="card o-hidden border-0 shadow-lg my-2">
      <div class="card-body p-5">
        <!-- Nested Row within Card Body -->
        <div class="row mb-2">
          <div class="col-lg-12">
            <h1>
              <i class="mdi mdi-monitor icon-3x text-gray-900"></i>
              <span class="h4 text-gray-900 mb-4">Equipment Checkout Request</span>
            </h1>
          </div>
        </div>
        <div class="row">
          <?php echo $this->Form->create('Checkout', array('url'=>'/checkout/submit'));?>
          <div class="col-lg-12">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <p>Select the type of devices you'd like to checkout and the dates in which you expect to keep them. Requests will be reviewed and approved or denied by the IT department.</p>
                </div>
              </div>

              <div class="row mb-2">
                <div class="col-md-4">Name: </div>
                <div class="col-md-8"><?php echo $this->Form->input('employee_name',array("label"=>false, 'div'=>false, 'class'=>'form-control')); ?></div>
              </div>

              <div class="row mb-2">
                <div class="col-md-4">Email: </div>
                <div class="col-md-8"><?php echo $this->Form->input('employee_email',array("label"=>false, 'div'=>false, 'class'=>'form-control')); ?></div>
              </div>

              <div class="row mb-2">
                <div class="col-sm-4">Check Out Date:</div>
                <div class="col-sm-8">
                  <?php echo $this->Form->input('checkout_date',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y')); ?>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-4">Check In Date:</div>
                <div class="col-sm-8">
                  <?php echo $this->Form->input('checkin_date',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y')); ?>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-4">Devices:</div>
                <div class="col-sm-8"><?php echo $this->Form->select('devices',$available,array('class'=>'custom-select','multiple'=>true,'label'=>false)) ?><br />
                Hold down the CTL key to select multiple device types.
                </div>
              </div>
              <div class="row">
                <div class="col-md-12"><?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary btn-block')) ?></div>
              </div>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
        <div class="row mt-5 pb-3">
          <div class="col-lg-12">
            <div class="copyright text-center my-auto">
              <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki" target="_blank" class="mr-3 h6"><i class="mdi mdi-information-outline icon-sm"> Documentation</i></a>
              <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory" class="h6"><i class="mdi mdi-github icon-sm""> View Source</i></a><br>
              Version <?php echo Configure::read('Settings.version') ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
