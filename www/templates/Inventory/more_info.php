<?= $this->Html->css('jquery-confirm.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery-confirm.min.js", "license-copy.js"], ['block'=>'script']) ?>
<?=
  //script to load the datatable
  $this->Html->scriptBlock("$(document).ready(function() {
    checkRunning();
    setInterval(checkRunning,40 * 1000);

      $('a.delete-computer').confirm({
        title: 'Delete Device',
        content: 'Are you sure you want to delete this device?',
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

<script type="text/javascript">
function checkRunning(){
$.getJSON('<?= $this->Url->webroot ?>ajax/checkRunning/<?= $computer['id'] ?>',function(data){
  if(data.received == data.transmitted)
  {
    $('#is_running').html('Running');
    $('#is_running').removeClass('text-danger');
  }
  else
  {
    if(<?= $settings['show_computer_commands']?>)
    {
      $('#is_running').html('Not Running <br /> <a href="#" onClick="wol(\'<?= $computer['MACaddress'] ?>\')">Turn On</a>');
      $('#is_running').removeClass('text-danger');
    }
    else
    {
      $('#is_running').html('Not Running');
      $('#is_running').addClass('text-danger');
      }
    }
  });
}

function expandTable(id){

  $('#' + id).toggle();

  toggleId = '#' + id + '-toggle';
  $(toggleId).toggleClass(['mdi-chevron-down','mdi-chevron-up']);

  return false;
}

function wol(mac){
  $.ajax('<?= $this->Url->webroot ?>ajax/wol?mac=' + mac);
}

function showOriginal(id, text){
  $('#' + id).html(text);

  return false;
}

</script>
<div class="row">
  <div class="col-xl-6 col-md-6 mb-4">
    <div class="row">
      <div class="col-lg-6">
        <div class="card border-left-success shadow h-500 py-1">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Device Type</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  <p><a href="<?= $this->Url->build('/search/search/5/' . $computer['device_type']['name']) ?>" class="text-gray-800"><?= $computer['device_type']['name'] ?></a></p>
                </div>
              </div>
              <div class="col-auto">
                <i class="mdi mdi-<?= $computer['device_type']['icon'] ?> icon-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <?php if($displayStatus): ?>
        <div class="card border-left-danger shadow h-500 py-1">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Current Status</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800"><p id="is_running" class="text-danger">Not Running</p></div>
              </div>
              <div class="col-auto">
                <i class="mdi mdi-information-outline icon-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-6 mb-4" align="right">
    <div class="btn-group">
      <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-cog-outline icon-sm text-white-50"></i> Actions
      </button>
      <div class="dropdown-menu">
        <a href="<?= $this->Url->build(array('action' => 'edit', $computer['id'])) ?>" class="dropdown-item"><i class="mdi mdi-square-edit-outline icon-sm icon-inline"></i> Edit</a>
        <div class="dropdown-divider"></div>
        <?php if($computer['device_type']['allow_decom'] == 'true'): ?>
        <a href="<?= $this->Url->build(array('action' => 'confirmDecommission', $computer['id'])) ?>" class="dropdown-item"><i class="mdi mdi-cancel icon-sm icon-inline"></i> Decommission</a>
        <?php endif; ?>
        <a href="<?= $this->Url->build(array('action' => 'delete', $computer['id'])) ?>" class="dropdown-item delete-computer"><i class="mdi mdi-delete icon-sm icon-inline"></i> Delete</a>
      </div>
    </div>
    <?php if($settings['enable_device_checkout'] == 'true'): ?>
      <?php if($computer['CanCheckout'] == 'true'): ?>
        <?php if($computer['IsCheckedOut'] == 'true'): ?>
      <a href="<?= $this->Url->build("/checkout/requests?q=" . $computer['ComputerName']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
        <i class="mdi mdi-cart-outline icon-sm text-white-50"></i> Checked Out
      </a>
        <?php else: ?>
      <a href="<?= $this->Url->build("/checkout/requests?q=" . $computer['ComputerName']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
        <i class="mdi mdi-cart-remove icon-sm text-white-50"></i> Available
      </a>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif ?>
    <?php if(file_exists(WWW_ROOT . '/drivers/' . str_replace(' ','_',$computer['Model']) . '.zip')): ?>
      <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build("/drivers/" . str_replace(' ','_',$computer['Model']) . ".zip") ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2"><i class="mdi mdi-download icon-sm text-white-50"></i> Download Drivers</a>
    <?php else: ?>
      <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/uploadDrivers/' . $computer['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2 popup fancybox.ajax"><i class="mdi mdi-upload icon-sm text-white-50"></i> Upload Drivers</a>
    <?php endif; ?>
  </div>
</div>

<?php if(array_key_exists('general', $tables) && count($tables['general']) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
        </div>
        <div class="card-body">
          <?= $this->AttributeDisplay->drawTable($tables['general'], $validAttributes, $computer); ?>
        </div>
      </div>
  </div>
</div>
<?php endif; ?>

<?php if(array_key_exists('hardware', $tables) && count($tables['hardware']) > 0): ?>
<?php $tableCount = 0; ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Hardware Information</h6>
        </div>
        <div class="card-body">
          <?= $this->AttributeDisplay->drawTable($tables['hardware'], $validAttributes, $computer); ?>
        </div>
      </div>
  </div>
</div>
<?php endif; ?>

<?php if(array_key_exists('network', $tables) && count($tables['network']) > 0): ?>
<?php $tableCount = 0; ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-info shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Network Information</h6>
        </div>
        <div class="card-body">
          <?= $this->AttributeDisplay->drawTable($tables['network'], $validAttributes, $computer); ?>
        </div>
      </div>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <?php if(count($computer['license_key']) > 0): ?>
  <div class="col-xl-7">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Licenses</h6>
      </div>
      <div class="card-body">
        <?php foreach($computer['license_key'] as $aLicense): ?>
        <div class="row">
          <div class="col-md-4"><?= $aLicense['license']['LicenseName'] ?></div>
          <div class="col-md-6">
            <a href="javascript:;" onclick="copyLicense('<?= $aLicense['id'] ?>')" id="license_<?= $aLicense['id'] ?>" style="cursor: copy">
              <?= $aLicense['Keycode'] ?>
            </a>
            <div id="js-copy-alert-<?= $aLicense['id'] ?>" class="text-success" style="display:none" role="alert"></div>
          </div>
          <div class="col-md-2">
            <a href="<?= $this->Url->build('/manage/reset_license/' . $aLicense['_joinData']['id']) ?>" class="text-danger">
              <i class="mdi mdi-delete icon-sm"></i>
            </a>
          </div>
        </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if($computer['notes'] != ''): ?>
  <div class="col-xl-5">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php if($computer['Computer']['notes'] != ''): ?>
          <?= $this->Markdown->transform($computer['Computer']['notes']); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php if(count($computer['application']) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><a href="#" onClick="return expandTable('applications')">Applications <i class="mdi mdi-chevron-down align-middle" id="applications-toggle"></i></a></h6>
      </div>
      <div class="card-body">
        <table id="applications" class="table table-striped" style="display:none">
          <?php foreach ($computer['application'] as $post): ?>
          <tr>
          <?php
              $row_class = '';

              if($post['monitoring'] == 'true')
              {
                $row_class = 'restricted';
              }
          ?>
          <td class="<?= $row_class ?>">
            <?= $this->Html->link( $post['full_name'], '/search/searchApplication/' . $post['id']); ?>
          </td>
          <td width="20%" class="<?= $row_class ?>" align="right">
            <?php if(key_exists($post['id'], $lifecycles)): ?>
            <a href="<?= $this->Url->build('/applications/lifecycle?q=' . $post['name']) ?>" title="Has Lifecycle" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"><i class="mdi mdi-update icon-sm icon-inline text-white-50"></i></a>
            <?php endif; ?>
            <a href="<?= $this->Url->build('/applications/unassign_application/' . $post['id'] . '/' . $computer['id']) ?>" title="Unassign Application" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="mdi mdi-close icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif;?>

 <?php if(count($services) > 0): ?>
 <div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><a href="#" onClick="return expandTable('services')">Services <i class="mdi mdi-chevron-down align-middle" id="services-toggle"></i></a></h6>
      </div>
      <div class="card-body">
        <table id="services" class="table table-striped" style="display:none">
          <?php foreach ($services as $post): ?>
          <tr>
            <td><?= $this->Html->link( $post['name'] , '/search/searchService/' . $post['name']); ?></td>
            <td><?= $post['startmode'] ?></td>
            <td><?= $post['status'] ?></td>
        </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
 </div>
 <?php endif ?>
