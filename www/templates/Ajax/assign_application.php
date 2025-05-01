<?= $this->Html->script(["bootstrap-autocomplete.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
    $('.auto-select').autoComplete();
   });", ["block"=>true])
?>
<h1 class="h3 mb-2 text-gray-800">Assign Application - <?= $application['name'] ?></h1>

<?= $this->Form->create(null, ['url'=>'/applications/assign_application']) ?>
<?= $this->Form->hidden('application_id', ['value'=>$application['id']]); ?>
<?= $this->Form->hidden('application_name', ['value'=>$application['name']]); ?>
<div class="row mt-3">
  <div class="col-sm-4">Assign To: </div>
  <div class="col-sm-8"><?= $this->Form->select('comp_id', [],  ['class'=>'custom-select auto-select', 'autocomplete'=>'off',
                                                                 'placeholder'=>'Start typing to search devices',
                                                                 'data-url'=>$this->Url->build('/ajax/search_device_list')]) ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
</div>
<?= $this->Form->end() ?>
