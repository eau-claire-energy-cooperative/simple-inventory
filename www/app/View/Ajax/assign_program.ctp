<h1 class="h3 mb-2 text-gray-800">Assign Program - <?php echo $program ?></h1>
<?php echo $this->Form->create('Programs',array('url'=>'/manage/restricted_programs')) ?>
<?php echo $this->Form->hidden('program',array('value'=>$program)); ?>
<?php echo $this->Form->hidden('version',array('value'=>$program_version)); ?>
<div class="row">
  <div class="col-sm-4">Assign To: </div>
  <div class="col-sm-8"><?php echo $this->Form->select('comp_id', $computers, array('label'=>false, 'empty'=>false, 'class'=>'custom-select')) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>
