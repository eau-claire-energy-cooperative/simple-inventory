<h1 class="h3 mb-2 text-gray-800">Set Profile Image</h1>

<?php echo $this->Form->create('Gravatar',array('url'=>"/inventory/set_profile_image")) ?>
<p>Add your <a href="https://gravatar.com/">Gravatar</a> email to set your profile image. Set this to blank for the default image.</p> 
<div class="row">
  <div class="col-sm-8"><?php echo $this->Form->input('username',array('class'=>'form-control','div'=>false,'label'=>false,'value'=>$username)); ?></div>
  <div class="col-sm-4"><?php echo $this->Form->submit('Upload',array('class'=>'btn btn-primary btn-user btn-block')) ?></div>
</div>

<?php echo $this->Form->end(); ?>