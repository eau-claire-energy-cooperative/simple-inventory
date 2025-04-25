<h1 class="h3 mb-2 text-gray-800">Extend Checkout</h1>
<?php echo $this->Form->create(null, array('url'=>'/checkout/extend')) ?>
<?php echo $this->Form->hidden('id',array('value'=>$req['id'])); ?>
<?php echo $this->Form->hidden('device_id', array('value'=>$req['computer'][0]['id'])) ?>
<p>Note: modifying the check in date will not force compatibility with downstream requests. Only do this if you are sure requests won't overlap with each other. </p>
<div class="row">
  <div class="col-sm-4">Original Check In Date: </div>
  <div class="col-sm-8"><?php echo $req['check_in_date']->i18nFormat('MM/dd/yyy') ?></div>
</div>
<div class="row mt-2">
  <div class="col-sm-4">New Check In Date: </div>
  <div class="col-sm-8">
    <?php echo $this->Form->input('check_in_date', array('class'=>'form-control', 'style'=>'width:40%; display:inline;', 'type'=>'date', 'dateFormat'=>'M-D-Y', "value"=>$req['check_in_date']->i18nFormat('yyyy-MM-dd'))) ?>
  </div>
</div>
<div class="row mt-3">
  <div class="col-sm-4"></div>
  <div class="col-sm-8"><?php echo $this->Form->Submit('Save',array('class'=>'btn btn-primary btn-block')) ?></div>
</div>
<?php echo $this->Form->end() ?>
