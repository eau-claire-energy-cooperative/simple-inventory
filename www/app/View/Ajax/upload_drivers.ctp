<h1 class="h3 mb-2 text-gray-800">Upload Drivers for <?php echo $computer['Model'] ?></h1>

<?php echo $this->Form->create('File',array('type'=>'file','url'=>"/inventory/do_drivers_upload")) ?>
<?php echo $this->Form->input('model',array('type'=>"hidden",'value'=>str_replace(' ','_',$computer['Model']))) ?>
<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$id)) ?>
<p>Upload Driver Zip Files:</p> 
<div class="row">
  <div class="col-sm-8"><?php echo $this->Form->input('local_file',array('type'=>'file','div'=>false,'label'=>false)); ?></div>
  <div class="col-sm-4"><?php echo $this->Form->submit('Upload',array('class'=>'btn btn-primary btn-user btn-block')) ?></div>
</div>

<?php echo $this->Form->end(); ?>
