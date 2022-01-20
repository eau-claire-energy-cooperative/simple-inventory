<div class="container" style="width:70%">
  <h1 class="h3 mb-2 text-gray-800">Assign End of Life Date</h1>
  <?php echo $this->Form->create('OperatingSystem',array('url'=>'/applications/operating_systems')) ?>
  <?php echo $this->Form->hidden('name', array('value'=>$osName)) ?>
  <div class="row">
  	<div class="col-sm-4">Operating System:</div>
  	<div class="col-sm-8"><?php echo $osName ?></div>
  </div>
  <div class="row mt-1">
    <div class="col-sm-4">End of Life:</div>
    <div class="col-sm-8"><?php echo $this->Form->input('eol_date', array('label'=>false, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y',)) ?></div>
  </div>
  <div class="row mt-2">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
  </div>
  <?php echo $this->Form->end() ?>
</div>
