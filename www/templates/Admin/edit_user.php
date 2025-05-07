
<?= $this->Form->create($user) ?>
<?= $this->Form->input('id',  ['type' => 'hidden']);?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4">Name: </div>
            <div class="col-md-8"><?= $this->Form->input('name', ['class'=>'form-control']);?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Username: </div>
            <div class="col-md-8"><?= $this->Form->input('username', ['class'=>'form-control']);?></div>
          </div>
          <?php if($settings['auth_type'] == 'local'): ?>
          <div class="row mb-2">
            <div class="col-md-4">Password: </div>
            <div class="col-md-8"><?= $this->Form->password('password', ['class'=>'form-control']) ?></div>
          </div>
          <?php else: ?>
           <?= $this->Form->input('password',array('type'=>'hidden')); ?>
          <?php endif; ?>
          <div class="row mb-2">
            <div class="col-md-4">Email: </div>
            <div class="col-md-8"><?= $this->Form->input('email', ['class'=>'form-control']);?></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Receive Emails: </div>
            <div class="col-md-8"><?= $this->Form->input('send_email', ['type' => 'select','options' => ['true'=>'Yes','false'=>'No'],'class'=>'custom-select']); ?></div>
          </div>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?= $this->Form->Submit('Update', ['class'=>'btn btn-primary btn-block']) ?></div>
          </div>
        </div>
    </div>
  </div>
</div>



<?= $this->Form->input('password_original',array('type'=>'hidden','value'=>$user['password'],'div'=>true,'label'=>true));

?>
<?= $this->Form->end();?>
