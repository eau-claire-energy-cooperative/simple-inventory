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
</script>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Url->build(array('controller' => 'applications', 'action' => 'lifecycle')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm mr-2"><i class="mdi mdi-chevron-left icon-sm icon-inline text-white-50"></i> Back</a>
</div>
<?php echo $this->Form->create(null, array('url'=>'/applications/lifecycle'));?>
<?php echo $this->Form->hidden('id', array('value'=>$lifecycle['id'])) ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <p>Define a lifecycle to be added to the system. Once assigned the application assigned to this lifecycle cannot be deleted. If changing the application keep in mind that individual devices will still point to the original application version.</p>
          <div class="row mb-2">
            <div class="col-md-4">Application: </div>
            <div class="col-md-8"><?php echo $this->Form->select('application_id',$applications,array('class'=>'custom-select','empty'=>false, 'value'=>$lifecycle['application_id'])) ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Update Frequency: </div>
            <div class="col-md-8"><?php echo $this->Form->input('update_frequency',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'value'=>$lifecycle['update_frequency'])) ?>
            This should be defined in <?php echo $this->Html->link('cron syntax', '/manage/commands#cron') ?>, default is monthly (1st of every month).</div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Last Check: </div>
            <div class="col-sm-4"><?php echo $this->Form->input('last_check',array('id'=>'last_check_date', 'class'=>'form-control', 'style'=>'display:inline;', 'type'=>'datetime-local', 'dateFormat'=>'M-D-Y', "value"=>$lifecycle['last_check']->i18nFormat('yyyy-MM-dd HH:mm'))); ?></div>
            <div class="col-sm-4"><a href="javascript:load_today()" class="btn btn-success">Today</a></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Notes: </div>
            <div class="col-sm-8"><a href="https://www.markdownguide.org/basic-syntax/">Markdown syntax</a> can be used to create links and other text within the notes.<br />
              <?php echo $this->Form->textarea('notes',array("label"=>false, 'div'=>false, 'class'=>'form-control', "value"=>$lifecycle['notes'])); ?></div>
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
