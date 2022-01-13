<?php echo $this->Form->create('Applications', array('url'=>'/applications/'));?>

<p>Create an application manually to be added to the system. You can assign it to devices using the "Assign To" button on the <a href="index">Applications</a> page.</p>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Application Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('name',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text')); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Version: </div>
            <div class="col-md-8"><?php echo $this->Form->input('version',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text')); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Monitor: </div>
            <div class="col-sm-8"><?php echo $this->Form->select('monitoring',array('true'=>'Yes','false'=>'No'),array('class'=>'custom-select','empty'=>false)) ?><br />
              Selecting Yes makes this application show up on reports when detected on a device.
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
