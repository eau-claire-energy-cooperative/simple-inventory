<?php
  echo $this->Html->script("jquery-confirm.min.js",false);

  echo $this->Html->css('jquery-confirm.min', array('inline'=>false));

  //script to load the confirm dialog
  echo $this->Html->scriptBlock("$(document).ready(function() {
    $('a.delete-license').confirm({
        title: 'Delete License',
        content: 'Are you sure you want to delete this license?',
        buttons: {
            yes: function(){
                location.href = this.\$target.attr('href');
            },
            cancel: function(){

            }
        }
    })

    });", array("inline"=>false));
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/manage/edit_license/'. $license['License']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i> Edit</a>
  <a href="<?php echo $this->Html->url('/manage/delete_license/' . $license['License']['id']) ?>" title="Delete License" data-title="Delete License" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-license"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Delete</a>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">License Information</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <table class="table table-striped">
            <tr>
              <th>License Name</th>
              <th>Vendor</th>
              <th>Expiration Date</th>
              <?php if($hasExpiration): ?>
              <th>Next Reminder</th>
              <?php endif; ?>
            </tr>
            <tr>
              <td><?php echo $license['License']['LicenseName']?></td>
              <td><?php echo $license['License']['Vendor']?></td>
              <td>
                <?php if($hasExpiration): ?>
                <?php echo $this->Time->format($license['License']['ExpirationDate'], '%m/%d/%Y') ?>
                <?php else: ?>
                  None
                <?php endif ?>
              </td>
              <?php if($hasExpiration): ?>
              <td><?php echo $next_reminder ?></td>
              <?php endif; ?>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php if($license['License']['Notes'] != ''): ?>
  <div class="col-xl-12">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php if($license['License']['Notes'] != ''): ?>
          <?php echo $this->Markdown->transform($license['License']['Notes']); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
