<?= $this->Html->script(["bootstrap-autocomplete.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
    $('.auto-select').autoComplete({preventEnter: true});

    $('.auto-select').on('autocomplete.select', function (evt, item) {
        // if value is blank put the original back
        if(item == null)
        {
					reset_lifecycle();
        }
		});
   });", ["block"=>true])
?>

<script>
function load_today(){
  today = new Date();

  month = today.getMonth() + 1;

  if(month < 10)
  {
    month = "0" + month;
  }

  $('#last_check_date').val(today.getFullYear() + "-" + month + "-" + today.getDate() + " " + today.getHours() + ":" + today.getMinutes());

}

function reset_lifecycle(){
  $('.auto-select').autoComplete('set', {value: <?= $lifecycle['application_id'] ?>, text: '<?= $lifecycle['application']['full_name'] ?>'});
}
</script>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build(['controller' => 'applications', 'action' => 'lifecycle']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm mr-2"><i class="mdi mdi-chevron-left icon-sm icon-inline text-white-50"></i> Back</a>
</div>
<?= $this->Form->create(null, ['url'=>'/applications/lifecycle']);?>
<?= $this->Form->hidden('id', ['value'=>$lifecycle['id']]) ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <p>Define a lifecycle to be added to the system. Once assigned the application assigned to this lifecycle cannot be deleted. If changing the application keep in mind that individual devices will still point to the original application version.</p>
          <div class="row mb-2">
            <div class="col-md-4">Application: </div>
            <div class="col-md-8"><?= $this->Form->select('application_id', [], ['class'=>'custom-select auto-select','empty'=>false, 'autocomplete'=>'off',
                                                                                           'data-default-value'=>$lifecycle['application_id'],
                                                                                           'data-default-text'=>$lifecycle['application']['full_name'],
                                                                                           'data-url'=>$this->Url->build('/ajax/search_application_list')]) ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Update Frequency: </div>
            <div class="col-md-8"><?= $this->Form->input('update_frequency', [ 'class'=>'form-control', 'value'=>$lifecycle['update_frequency']]) ?>
            This should be defined in <?= $this->Html->link('cron syntax', '/manage/commands#cron') ?>, default is monthly (1st of every month).</div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Last Check: </div>
            <div class="col-sm-4"><?= $this->Form->input('last_check', ['id'=>'last_check_date', 'class'=>'form-control', 'style'=>'display:inline;', 'type'=>'datetime-local', 'dateFormat'=>'M-D-Y', "value"=>$lifecycle['last_check']->i18nFormat('yyyy-MM-dd HH:mm')]); ?></div>
            <div class="col-sm-4"><a href="javascript:load_today()" class="btn btn-success">Today</a></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Notes: </div>
            <div class="col-sm-8"><a href="https://www.markdownguide.org/basic-syntax/">Markdown syntax</a> can be used to create links and other text within the notes.<br />
              <?= $this->Form->textarea('notes', ['class'=>'form-control', "value"=>$lifecycle['notes']]); ?></div>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?= $this->Form->Submit('Save', ['class'=>'btn btn-primary btn-block']) ?></div>
          </div>
          <?= $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
