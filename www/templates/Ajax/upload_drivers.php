<?= $this->Html->script("bs-custom-file-input.js", ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
    bsCustomFileInput.init();
   });", ["block"=>true])
?>

<h1 class="h3 mb-2 text-gray-800">Upload Drivers for <?php echo $computer['ComputerName'] ?></h1>
<?php echo $this->Form->create(null, array('type'=>'file','url'=>"/inventory/do_drivers_upload")) ?>
<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$computer['id'])) ?>
<div class="row">
  <div class="col-sm-12">
    <p>File upload sizes may be limited by your web server or PHP config. To upload larger files for this device you can put them in the <code>/drivers</code> directory with the name <code><?= $computer['driver_filename'] ?></code></p>
  </div>
  <div class="col-sm-8">
    <div class="custom-file">
      <input type="file" class="custom-file-input" id="local_file" name="local_file">
      <label class="custom-file-label" for="csvFile">Choose file</label>
    </div>
  </div>
  <div class="col-sm-4"><?php echo $this->Form->submit('Upload',array('class'=>'btn btn-primary btn-user btn-block')) ?></div>
</div>

<?php echo $this->Form->end(); ?>
