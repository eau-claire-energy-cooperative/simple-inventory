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

    $('a.delete-license-key').confirm({
        title: 'Delete License Key',
        content: 'Are you sure you want to delete this license key?',
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
              <?php if($this->License->hasExpiration($license)): ?>
              <th>Start Reminders</th>
              <?php endif; ?>
            </tr>
            <tr>
              <td><?php echo $license['License']['LicenseName']?></td>
              <td><?php echo $license['License']['Vendor']?></td>
              <td class="<?php echo $this->License->expirationCSS($license['License']['ExpirationDate'], $license['License']['StartReminder']) ?>">
                <?php if($this->License->hasExpiration($license)): ?>
                <?php echo $this->Time->format($license['License']['ExpirationDate'], '%m/%d/%Y') ?>
                <?php else: ?>
                  None
                <?php endif ?>
              </td>
              <?php if($this->License->hasExpiration($license)): ?>
              <td><?php echo $this->License->calcReminder($license['License']['ExpirationDate'], $license['License']['StartReminder']) ?></td>
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

  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">License Keys</h6>
      </div>
      <div class="card-body">
        <div class="mb-3" align="right">
          <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/new_license_key/' . $license['License']['id']) ?>" class="d-none d-sm-inline-block btn btn-primary btn-sm shadow-sm"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Key</a>
          <a href="<?php echo $this->Html->url('/search/searchLicense/license/' . $license['License']['id']) ?>" class="d-none d-sm-inline-block btn btn-success btn-sm shadow-sm"><i class="mdi mdi-eye-outline icon-sm icon-inline text-white-50"></i> View Assigned</a>
        </div>
        <?php if(count($license['LicenseKey']) > 0): ?>
        <table class="table table-striped">
          <tr>
            <th>License Key</th>
            <th>Assigned</th>
            <th>Total</th>
            <th></th>
          </tr>
          <?php foreach($license['LicenseKey'] as $key): ?>
          <tr>
            <td><?php echo $key['Keycode'] ?></td>
            <td><?php echo $this->Html->link(count($key['Computer']), '/search/searchLicense/key/' . $key['id']) ?></td>
            <td><?php echo $key['Quantity'] ?></td>
            <td align="right">
              <?php if (count($key['Computer']) < $key['Quantity']): ?>
              <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/assign_license_key/' . $license['License']['id'] . '/' . $key['id']) ?>" title="Assign License Key" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
              <?php endif ?>
              <?php if (count($key['Computer']) == 0): ?>
              <a href="<?php echo $this->Html->url('/manage/delete_license_key/' . $license['License']['id'] . '/' . $key['id']) ?>" title="Delete License" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-license-key"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach ?>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
