
<?php echo $this->Form->create('DeviceType', array('url' => '/admin/editDeviceType')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">Device Type Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('name',array('label'=>false, 'div'=>false, 'class'=>'form-control'));?></div>
          </div>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Update',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
