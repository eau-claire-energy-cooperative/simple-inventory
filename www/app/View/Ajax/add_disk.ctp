<div class="container" style="width:65%">
  <h1 class="h3 mb-2 text-gray-800">Add Disk</h1>
  <?php echo $this->Form->create('Disk',array('url'=>'/inventory/add_disk')) ?>
  <?php echo $this->Form->hidden('comp_id', array('value'=>$comp_id)) ?>
  <?php echo $this->Form->hidden('type', array('value'=>'Local')) ?>
  <div class="row">
  	<div class="col-sm-4">Label:</div>
  	<div class="col-sm-8"><?php echo $this->Form->input('label', array('div'=>false, 'label'=>false, 'class'=>'form-control')) ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">Total Space (in KB):</div>
    <div class="col-sm-8"><?php echo $this->Form->input('total_space', array('label'=>false, 'class'=>'form-control')) ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">Free Space (in KB): </div>
    <div class="col-sm-8"><?php echo $this->Form->input('space_free', array('div'=>false, 'label'=>false, 'class'=>'form-control')) ?></div>
  </div>
  <div class="row mt-2">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
  </div>
  <?php echo $this->Form->end() ?>
</div>
