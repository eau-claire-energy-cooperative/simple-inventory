<?php echo $this->Form->create('Computer', array('url' => '/Inventory/edit')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<div class="mb-4" align="right">
      <?php echo $this->Form->Submit('Update',array('class'=>'btn btn-primary')) ?>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">Computer Name</div>
            <div class="col-md-8"><?php echo $this->Form->input('ComputerName', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Current Location</div>
              <div class="col-md-8"><?php echo $this->Form->input('ComputerLocation',array('class'=>'custom-select','label'=>false,'type' => 'select', 'id' => 'location_id', 'options' => $location)); ?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Current User</div>
              <div class="col-md-8"><?php echo $this->Form->input('CurrentUser', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Serial Number</div>
              <div class="col-md-8"><?php echo $this->Form->input('SerialNumber', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Asset ID</div>
              <div class="col-md-8"><?php echo $this->Form->input('AssetId', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
        </div>    
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Hardware Information</h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">Model</div>
            <div class="col-md-8"><?php echo $this->Form->input('Model', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Operating System</div>
              <div class="col-md-8"><?php echo $this->Form->input('OS', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">CPU</div>
              <div class="col-md-8"><?php echo $this->Form->input('CPU', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Memory</div>
              <div class="col-md-8"><?php echo $this->Form->input('Memory', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">Number of Monitors</div>
              <div class="col-md-8"><?php echo $this->Form->input('NumberOfMonitors', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
        </div>    
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Network Information</h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">IP Address</div>
            <div class="col-md-8"><?php echo $this->Form->input('IPaddress', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
              <div class="col-md-4">MAC Address</div>
              <div class="col-md-8"><?php echo $this->Form->input('MACaddress', array('label'=>false,'class'=>'form-control'));?></div>
          </div>
        </div>    
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-8">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php echo $this->Form->input('notes', array('label'=>false,'class'=>'form-control')); ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->Form->end();?>
