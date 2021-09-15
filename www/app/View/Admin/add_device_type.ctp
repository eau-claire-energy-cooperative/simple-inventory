<?php echo $this->Form->create('DeviceType');?>

<p>Device Types are used to assign attributes to specific types of device classes. Examples may be computers, phones, or printers. The icon class is the CSS class as indicated by the <a href="https://fontawesome.com/v5.15/icons?d=gallery&p=2">font awesome</a> library.
If you can't find an icon you like consider using the basic <i>fa-desktop</i> icon.</p>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Device Type Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('name',array("label"=>false, 'div'=>false, 'class'=>'form-control')); ?></div>
          </div>
          <div class="row">
            <div class="col-md-4">Icon: </div>
            <div class="col-md-8"><?php echo $this->Form->input('icon',array("label"=>false, 'div'=>false, 'class'=>'form-control')); ?></div>
          </div>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
