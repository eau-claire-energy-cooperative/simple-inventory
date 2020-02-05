<h2>Upload Drivers for <?php echo $computer['Model'] ?></h2>

<?php echo $this->Form->create('File',array('type'=>'file','url'=>"/inventory/do_drivers_upload")) ?>

<p>Upload Driver Zip Files:</p> 
<?php echo $this->Form->input('model',array('type'=>"hidden",'value'=>str_replace(' ','_',$computer['Model']))) ?>
<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$id)) ?>
<?php echo $this->Form->input('local_file',array('type'=>'file','div'=>false,'label'=>false)); ?>
<div align="right"><?php echo $this->Form->end("Upload"); ?></div>
