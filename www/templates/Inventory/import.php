<?= $this->Html->script("bs-custom-file-input.js", ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
    bsCustomFileInput.init();
   });", ["block"=>true])
?>
<div class="card shadow mb-4">
    <div class="card-body">
      <p class="mb-2">Upload a CSV file to bulk import devices. The file must look like the example below with the first two columns indicating the device type (slug) and name of the device to add. Do not add a header row. The <b>location</b> of the device will be set to the default location as specified in the <?= $this->Html->link('location','/admin/location') ?> area.</p>
      <p><b>Example File</b></p>
      <pre class="bg-light">
  computer, COMPUTER1
  phone, PHONE1
      </pre>
      <?= $this->Form->create(null, ['type'=>'file','url'=>"/inventory/import"]) ?>
      <?= $this->Form->input('model', ['type'=>"hidden",'value'=>'import_devices']) ?>
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Import File: </label>
        <div class="col-md-6">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="csvFile" name="csvFile">
            <label class="custom-file-label" for="csvFile">Choose file</label>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-6"><?= $this->Form->Submit('Upload', ['class'=>'btn btn-primary btn-block']) ?></div>
        <div class="col-md-2"></div>
      </div>
      <?= $this->Form->end(); ?>
  </div>
</div>

<?php if(isset($results)): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Import Results</h6>
    </div>
    <div class="card-body">
      <?php foreach($results as $computer): ?>
        <?= $this->Html->link($computer['ComputerName'], '/inventory/moreInfo/' . $computer['id']) ?><br />
      <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
