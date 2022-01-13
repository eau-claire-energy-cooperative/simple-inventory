<?php echo $this->Form->create('Programs', array('url'=>'/manage/restricted_programs'));?>

<p>Create a program manually to be added to the system. You must assign this program to a device. You can assign to more devices using the "Assign To" button on the <a href="restricted_programs">Programs</a> page.</p>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Program Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('program',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text')); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Version: </div>
            <div class="col-md-8"><?php echo $this->Form->input('version',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text')); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Device Assigned:</div>
            <div class="col-sm-8"><?php echo $this->Form->select('comp_id',$computers,array('class'=>'custom-select','multiple'=>false,'label'=>false)) ?></div>
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
