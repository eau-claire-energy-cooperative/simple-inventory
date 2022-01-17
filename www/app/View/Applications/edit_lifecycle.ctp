<script>
function load_today(){
  today = new Date();

  month = today.getMonth() + 1;

  if(month < 10)
  {
    month = "0" + month;
  }

  $('#LifecycleLastCheckMonth').val(month);
  $('#LifecycleLastCheckDay').val(today.getDate());
  $('#LifecycleLastCheckYear').val(today.getFullYear());
}
</script>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url(array('controller' => 'applications', 'action' => 'lifecycle')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm mr-2"><i class="mdi mdi-chevron-left icon-sm icon-inline text-white-50"></i> Back</a>
</div>
<?php echo $this->Form->create('Lifecycle', array('url'=>'/applications/lifecycle'));?>
<?php echo $this->Form->hidden('id', array('value'=>$lifecycle['Lifecycle']['id'])) ?>
<p>Define a lifecycle to be added to the system. Once assigned the application assigned to this lifecycle cannot be deleted. If changing the application keep in mind that individual devices will still point to the original application version.</p>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Application: </div>
            <div class="col-md-8"><?php echo $this->Form->select('application_id',$applications,array('class'=>'custom-select','empty'=>false, value=>$lifecycle['Lifecycle']['application_id'])) ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Update Frequency: </div>
            <div class="col-md-8"><?php echo $this->Form->input('update_frequency',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'value'=>$lifecycle['Lifecycle']['update_frequency'])) ?>
            This should be defined in cron syntax, default is monthly (1st of every month).</div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Last Check: </div>
            <div class="col-sm-6"><?php echo $this->Form->input('last_check',array("label"=>false, 'div'=>false, 'class'=>'form-control', 'style'=>'width:30%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y', "value"=>$lifecycle['Lifecycle']['last_check'])); ?></div>
            <div class="col-sm-2"><a href="javascript:load_today()" class="btn btn-success">Today</a></div>
          </div>
          <div class="row mb-2">
            <div class="col-sm-4">Notes: </div>
            <div class="col-sm-8"><?php echo $this->Form->input('notes',array("label"=>false, 'div'=>false, 'class'=>'form-control', "value"=>$lifecycle['Lifecycle']['notes'])); ?></div>
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
