<div class="row">
  <div class="col-lg-12">
    <div class="p-5">
      <div class="text-center">
        <p><i class="mdi mdi-monitor icon-4x text-gray-900"></i></p>
        <h1 class="h4 text-gray-900 mb-4">Simple Inventory</h1>
      </div>
      <?php echo $this->Form->create('User',array('url'=>'/inventory/login')) ?>
        <div class="form-group">
          <?php echo $this->Form->input('username', array('class'=>'form-control form-control-user', 'placeholder'=>'Username', 'div'=>false, 'label'=>false)) ?>
        </div>
        <div class="form-group">
          <?php echo $this->Form->input('password', array('class'=>'form-control form-control-user', 'placeholder'=>'Password', 'div'=>false, 'label'=>false)) ?>
        </div>
        <?php echo $this->Form->submit('Login', array('class'=>'btn btn-primary btn-user btn-block')) ?>
        <?php echo $this->Form->end() ?>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#UserUsername').focus();
});
</script>
