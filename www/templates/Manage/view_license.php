<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->script("license-copy.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
    $this->Html->scriptBlock("$(document).ready(function() {
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
     });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/manage/edit_license/'. $license['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i> Edit</a>
  <a href="<?= $this->Url->build('/manage/delete_license/' . $license['id']) ?>" title="Delete License" data-title="Delete License" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-license"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Delete</a>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
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
              <td><?= $license['LicenseName']?></td>
              <td><?= $license['Vendor']?></td>
              <td class="<?= $this->License->expirationCSS($license['ExpirationDate'], $license['StartReminder']) ?>">
                <?php if($this->License->hasExpiration($license)): ?>
                  <?= $license->ExpirationDate->i18nFormat('MM/dd/YY') ?>
                <?php else: ?>
                  None
                <?php endif ?>
              </td>
              <?php if($this->License->hasExpiration($license)): ?>
              <td><?= $this->License->calcReminder($license['ExpirationDate'], $license['StartReminder']) ?></td>
              <?php endif; ?>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php if($license['Notes'] != ''): ?>
  <div class="col-xl-12">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php if($license['Notes'] != ''): ?>
          <?php //echo $this->Markdown->transform($license['License']['Notes']); ?>
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
          <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/new_license_key/' . $license['id']) ?>" class="d-none d-sm-inline-block btn btn-primary btn-sm shadow-sm"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Key</a>
          <a href="<?= $this->Url->build('/search/searchLicense/license/' . $license['id']) ?>" class="d-none d-sm-inline-block btn btn-success btn-sm shadow-sm"><i class="mdi mdi-eye-outline icon-sm icon-inline text-white-50"></i> View Assigned</a>
        </div>
        <?php if(count($license['license_key']) > 0): ?>
        <table class="table table-striped">
          <tr>
            <th>License Key</th>
            <th>Assigned</th>
            <th>Total</th>
            <th></th>
          </tr>
          <?php foreach($license['license_key'] as $key): ?>
          <tr>
            <td>
              <a href="javascript:;" onclick="copyLicense('<?= $key['id'] ?>')" id="license_<?= $key['id'] ?>" style="cursor: copy">
                <?= $key['Keycode'] ?>
              </a>
              <div id="js-copy-alert-<?= $key['id'] ?>" class="text-success" style="display:none" role="alert"></div>
            </td>
            <td><?= $this->Html->link(count($key['computer']), '/search/searchLicense/key/' . $key['id']) ?></td>
            <td><?= $key['Quantity'] ?></td>
            <td align="right">
              <?php if (count($key['computer']) < $key['Quantity']): ?>
              <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/assign_license_key/' . $license['id'] . '/' . $key['id']) ?>" title="Assign License Key" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
              <?php endif ?>
              <?php if (count($key['computer']) == 0): ?>
              <a href="<?= $this->Url->build('/manage/delete_license_key/' . $license['id'] . '/' . $key['id']) ?>" title="Delete License" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-license-key"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
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
