
<?php echo $this->Form->create('Setting');?>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <?php if(isset($setting)): ?>
          <?php echo $this->Form->hidden('id',array('value'=>$setting['Setting']['id'])); ?>
          <div class="row mb-1">
            <div class="col-md-4">Setting Key: </div>
            <div class="col-md-8"><?php echo $this->Form->input('key',array('class'=>'form-control',"label"=>false,'value'=>$setting['Setting']['key'])); ?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Setting Value: </div>
            <div class="col-md-8"><?php echo $this->Form->input('value',array('class'=>'form-control',"label"=>false,'value'=>$setting['Setting']['value'])); ?></div>
          </div>
          <?php else: ?>
          <div class="row mb-1">
            <div class="col-md-4">Setting Key: </div>
            <div class="col-md-8"><?php echo $this->Form->input('key',array('class'=>'form-control',"label"=>false)); ?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Setting Value: </div>
            <div class="col-md-8"><?php echo $this->Form->input('value',array('class'=>'form-control',"label"=>false)); ?></div>
          </div>
          <?php endif; ?>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>