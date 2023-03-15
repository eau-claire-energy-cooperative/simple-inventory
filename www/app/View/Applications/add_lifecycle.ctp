<?php echo $this->Form->create('Lifecycle', array('url'=>'/applications/lifecycle'));?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <p>Define a lifecycle to be added to the system. Once assigned the application assigned to this lifecycle cannot be deleted.</p>
          <div class="row mb-2">
            <div class="col-md-4">Application: </div>
            <div class="col-md-8"><?php echo $this->Form->select('application_id',$applications,array('class'=>'custom-select','empty'=>false)) ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Update Frequency: </div>
            <div class="col-md-8"><?php echo $this->Form->input('update_frequency',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'value'=>"0 0 1 * *")) ?>
            This should be defined in <?php echo $this->Html->link('cron syntax', '/manage/commands#cron') ?>, default is monthly (1st of every month).</div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Last Check: </div>
            <div class="col-sm-8"><?php echo $this->Form->input('last_check',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'style'=>'width:20%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y')); ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Notes: </div>
            <div class="col-sm-8"><a href="https://www.markdownguide.org/basic-syntax/">Markdown syntax</a> can be used to create links and other text within the notes.<br />
              <?php echo $this->Form->input('notes',array("label"=>false, 'div'=>false, 'class'=>'form-control')); ?></div>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>
