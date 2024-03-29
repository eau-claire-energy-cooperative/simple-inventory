
<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/inventory/moreInfo/' . $computer_id) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="mdi mdi-chevron-left icon-sm icon-inline text-white-50"></i> Back </a>
</div>

<?php

echo $this->Form->create('Computer');

//create two variables for populating options
$options=array('Yes' => 'Yes', 'No' => 'No');
$attributes=array('legend' => false,'separator'=>'<br>');

?>
<?php if(isset($errors)): ?>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Warning</h6>
        </div>
        <div class="card-body">
          <p><?php echo $errors ?></p>
        </div>
    </div>
  </div>
</div>
<?php else: ?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Questions</h6>
        </div>
        <div class="card-body">
          <table class="table">
            <tr>
                <td class="radio"><p>Did you wipe the hard drive?</p><?php echo $this->Form->radio('WipedHD', $options, $attributes);?></td>
                <td><?php echo $this->Form->input('RedeployedAs', array('class'=>'form-control')) ?></td>
            </tr>
            <tr>
                <td class="radio"><p>Was the machine recycled?</p><?php echo $this->Form->radio('Recycled', $options, $attributes);?></td>
                <td><?php echo $this->Form->input('notes', array('rows' => '3','value'=>$this->data['Computer']['notes'], 'class'=>'form-control')) ?></td>
            </tr>
          </table>
          <?php echo $this->Form->input('id', array('type' => 'hidden'));?>
          <?php echo $this->Form->submit('Update', array('class'=>'btn btn-primary'));?>
          <?php echo $this->Form->end(); ?>
        </div>
    </div>
  </div>
</div>

<?php endif ?>
