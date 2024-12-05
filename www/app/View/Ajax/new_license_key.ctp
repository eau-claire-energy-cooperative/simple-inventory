<h1 class="h3 mb-2 text-gray-800">New License Key</h1>
<?php echo $this->Form->create('LicenseKey',array('url'=>'/manage/view_license/' . $license_id)) ?>
<?php echo $this->Form->hidden('license_id', array('value'=>$license_id)) ?>
<div class="row mt-1 mb-2">
  <div class="col-sm-4">License Key: </div>
  <div class="col-sm-8"><?php echo $this->Form->textarea('Keycode', array('div'=>false, 'label'=>false, 'class'=>'form-control', 'rows'=>4)) ?></div>
</div>
<div class="row mt-1">
  <div class="col-sm-4">Quantity: </div>
  <div class="col-sm-8"><?php echo $this->Form->input('Quantity', array('label'=>false, 'empty'=>false, 'value'=>1, 'class'=>'form-control')) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>
