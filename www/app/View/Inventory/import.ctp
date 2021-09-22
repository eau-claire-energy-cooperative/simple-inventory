<div class="card shadow mb-4">
    <div class="card-body">
      <p class="mb-2">Upload a CSV file to bulk import devices. The file must look like the example below with the first two columns indicating the device type (slug) and name of the device to add. Do not add a header row. The <b>location</b> of the device will be set to the default location as specified in the <?php echo $this->Html->link('location','/admin/location') ?> area.</p>
      <p><b>Example File</b></p>
      <pre class="bg-light">
  computer, COMPUTER1
  phone, PHONE1
      </pre>
      <?php echo $this->Form->create('File',array('type'=>'file','url'=>"/inventory/import")) ?>
      <?php echo $this->Form->input('model',array('type'=>"hidden",'value'=>'import_devices')) ?>
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Import File: </label>
        <div class="col-md-6"><?php echo $this->Form->input('local_file',array('type'=>'file','div'=>false,'label'=>false)); ?></div>
      </div>
      <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-6"><?php echo $this->Form->Submit('Upload',array('class'=>'btn btn-primary btn-block')) ?></div>
        <div class="col-md-2"></div>
      </div>
      <?php echo $this->Form->end(); ?>
  </div>
</div>

<?php if(isset($results)): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Import Results</h6>
    </div>
    <div class="card-body">
      <?php foreach($results as $computer): ?>
        <?php echo $this->Html->link($computer['ComputerName'], '/inventory/moreInfo/' . $computer['id']) ?><br />
      <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
