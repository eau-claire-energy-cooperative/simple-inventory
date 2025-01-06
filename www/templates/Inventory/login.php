<div class="row">
  <div class="col-lg-12">
    <div class="p-5">
      <div class="text-center">
        <p><i class="mdi mdi-monitor icon-4x text-gray-900"></i></p>
        <h1 class="h4 text-gray-900 mb-4">Simple Inventory</h1>
      </div>
      <?= $this->Form->create(null, ['url'=>'/inventory/login']) ?>
        <div class="form-group">
          <?= $this->Form->input('username', ['id'=>'username', 'class'=>'form-control form-control-user', 'placeholder'=>'Username']) ?>
        </div>
        <div class="form-group">
          <?= $this->Form->password('password', ['class'=>'form-control form-control-user', 'placeholder'=>'Password']) ?>
        </div>
        <?= $this->Form->submit('Login', ['class'=>'btn btn-primary btn-user btn-block']) ?>
        <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#username').focus();
});
</script>
