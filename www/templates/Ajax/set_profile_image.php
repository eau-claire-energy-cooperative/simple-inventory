<h1 class="h3 mb-2 text-gray-800">Set Profile Image</h1>

<?= $this->Form->create(null, ['url'=>"/inventory/set_profile_image"]) ?>
<p>Add your <a href="https://gravatar.com/" target="_blank">Gravatar</a> email to set your profile image. Set this to blank for the default image.</p>
<div class="row">
  <div class="col-sm-8"><?= $this->Form->input('username', ['class'=>'form-control', 'value'=>$username]); ?></div>
  <div class="col-sm-4"><?= $this->Form->submit('Save', ['class'=>'btn btn-primary btn-user btn-block']) ?></div>
</div>

<?= $this->Form->end(); ?>
