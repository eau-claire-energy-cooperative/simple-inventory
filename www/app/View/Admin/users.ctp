<?php
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));

    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {

        $('a.delete-user').confirm({
          content: 'Are you sure you want to delete this user?',
          buttons: {
              yes: function(){
                  location.href = this.\$target.attr('href');
              },
              cancel: function(){

              }
          }
        });
     });", array("inline"=>false))
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/admin/editUser') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50">add</i> Add User</a>
</div>
<?php foreach ($users as $aUser): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><?php echo $aUser['User']['name'] ?></h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-4">Username: </div>
                <div class="col-md-8"><?php echo $aUser['User']['username'] ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-4">Email: </div>
                <div class="col-sm-8"><?php echo $aUser['User']['email'] ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-4">Send Admin Email: </div>
                <div class="col-sm-8"><?php echo ucwords($aUser['User']['send_email']) ?></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <a href="<?php echo $this->Html->url(array('action' => 'editUser', $aUser['User']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50">edit</i> Edit</a>
                  <a data-title="Delete User" href="<?php echo $this->Html->url("/admin/editUser/". $aUser['User']['id'] ."?action=delete") ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-user"><i class="material-icons mi-sm mi-inline text-white-50">delete</i> Delete</a>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
