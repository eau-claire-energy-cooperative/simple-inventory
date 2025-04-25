<?= $this->Form->create(null, ['url'=>'/applications/lifecycle']);?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <p>Define a lifecycle to be added to the system. Once assigned the application assigned to this lifecycle cannot be deleted.</p>
          <div class="row mb-2">
            <div class="col-md-4">Application: </div>
            <div class="col-md-8"><?= $this->Form->select('application_id',$applications, ['class'=>'custom-select','empty'=>false]) ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Update Frequency: </div>
            <div class="col-md-8"><?= $this->Form->input('update_frequency', ['class'=>'form-control', 'value'=>"0 0 1 * *"]) ?>
            This should be defined in <?= $this->Html->link('cron syntax', '/manage/commands#cron') ?>, default is monthly (1st of every month).</div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Last Check: </div>
            <div class="col-sm-8"><?= $this->Form->input('last_check', ['class'=>'form-control', 'style'=>'width:20%; display:inline;', 'type'=>'datetime-local', 'dateFormat'=>'M-D-Y', 'value'=>$today->i18nFormat("yyyy-MM-dd HH:mm")]); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Notes: </div>
            <div class="col-sm-8"><a href="https://www.markdownguide.org/basic-syntax/">Markdown syntax</a> can be used to create links and other text within the notes.<br />
              <?= $this->Form->textarea('notes', ['class'=>'form-control']); ?></div>
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
