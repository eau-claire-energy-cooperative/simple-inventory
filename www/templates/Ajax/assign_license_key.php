<?= $this->Html->script(["bootstrap-autocomplete.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
    $('.auto-select').autoComplete({preventEnter: true});
   });", ["block"=>true])
?>
<h1 class="h3 mb-2 text-gray-800">Assign License</h1>
<?= $this->Form->create(null, ['url'=>'/manage/view_license/' . $license_id]) ?>
<?= $this->Form->hidden('license_key_id', ['value'=>$license_key_id]); ?>
<?= $this->Form->hidden('license_action', ['value'=>'assign']); ?>
<div class="row">
  <div class="col-sm-4">Assign License To: </div>
  <div class="col-sm-8"><?= $this->Form->select('computer', [], ['empty'=>false, 'class'=>'custom-select auto-select', 'autocomplete'=>'off',
                                                                 'placeholder'=>'Start typing to search devices',
                                                                 'data-url'=>$this->Url->build('/ajax/search_device_list')]) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
</div>
<?= $this->Form->end() ?>
