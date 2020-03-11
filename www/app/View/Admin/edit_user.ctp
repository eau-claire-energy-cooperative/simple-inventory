

<table>
		
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
	</tr>
</table>


<?php echo $this->Form->create('User',array('url'=>'/admin/editUser')) ?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
          <div class="row mb-1">
            <div class="col-md-4">Name: </div>
            <div class="col-md-8"><?php echo $this->Form->input('name',array('label'=>false, 'div'=>false, 'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Username: </div>
            <div class="col-md-8"><?php echo $this->Form->input('username',array('label'=>false, 'div'=>false, 'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Password: </div>
            <div class="col-md-8"><?php echo $this->Form->input('password',array('label'=>false, 'div'=>false, 'class'=>'form-control')) ?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Email: </div>
            <div class="col-md-8"><?php echo $this->Form->input('email',array('label'=>false, 'div'=>false, 'class'=>'form-control'));?></div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">Send Email: </div>
            <div class="col-md-8"><?php echo $this->Form->input('send_email',array('div'=>false,'label'=>false,'type' => 'select','options' => array('true'=>'Yes','false'=>'No'),'class'=>'custom-select')); ?></div>
          </div>
          <div class="row mt-2">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?php echo $this->Form->Submit('Update',array('class'=>'btn btn-primary btn-block')) ?></div>
          </div>
        </div>
    </div>
  </div>
</div>

<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<?php if(isset($this->data['User']['id'])) {
  echo $this->Form->input('password_original',array('type'=>'hidden','value'=>$this->data['User']['password'],'div'=>true,'label'=>true));  
}
?>
<?php echo $this->Form->end();?>
