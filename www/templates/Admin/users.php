<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
    $this->Html->scriptBlock("$(document).ready(function() {
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
     });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/admin/editUser') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add User</a>
</div>
<?php foreach ($users as $aUser): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><?= $aUser['name'] ?></h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-4">Username: </div>
                <div class="col-md-8"><?= $aUser['username'] ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-4">Email: </div>
                <div class="col-sm-8"><?= $aUser['email'] ?></div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-4">Receive Emails: </div>
                <div class="col-sm-8"><?= strtolower($aUser['send_email']) == 'true' ? 'Yes' : 'No'; ?></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <a href="<?= $this->Url->build(array('action' => 'editUser', $aUser['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i> Edit</a>
                  <a data-title="Delete User" href="<?= $this->Url->build("/admin/editUser/". $aUser['id'] ."?action=delete") ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-user"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Delete</a>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
