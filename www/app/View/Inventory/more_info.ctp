<?php
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->script("license-copy.js",false);
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
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
     });", array("inline"=>false))
?>

<script type="text/javascript">
function checkRunning(){
$.getJSON('<?php echo $this->webroot ?>ajax/checkRunning/<?php echo $computer['Computer']['id'] ?>',function(data){
  if(data.received == data.transmitted)
  {
    $('#is_running').html('Running');
    $('#is_running').removeClass('text-danger');
  }
  else
  {
    if(<?php echo $settings['show_computer_commands']?>)
    {
      $('#is_running').html('Not Running <br /> <a href="#" onClick="wol(\'<?php echo $computer['Computer']['MACaddress'] ?>\')">Turn On</a>');
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
  $.ajax('<?php echo $this->webroot ?>ajax/wol?mac=' + mac);
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
                  <p><a href="<?php echo $this->Html->url('/search/search/5/' . $computer['DeviceType']['name']) ?>" class="text-gray-800"><?php echo $computer['DeviceType']['name'] ?></a></p>
                </div>
              </div>
              <div class="col-auto">
                <i class="mdi mdi-<?php echo $computer['DeviceType']['icon'] ?> icon-2x text-gray-300"></i>
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
        <a href="<?php echo $this->Html->url(array('action' => 'edit', $computer['Computer']['id'])) ?>" class="dropdown-item"><i class="mdi mdi-square-edit-outline icon-sm icon-inline"></i> Edit</a>
        <div class="dropdown-divider"></div>
        <?php if($computer['DeviceType']['allow_decom'] == 'true'): ?>
        <a href="<?php echo $this->Html->url(array('action' => 'confirmDecommission', $computer['Computer']['id'])) ?>" class="dropdown-item"><i class="mdi mdi-cancel icon-sm icon-inline"></i> Decommission</a>
        <?php endif; ?>
        <a href="<?php echo $this->Html->url(array('action' => 'delete', $computer['Computer']['id'])) ?>" class="dropdown-item delete-computer"><i class="mdi mdi-delete icon-sm icon-inline"></i> Delete</a>
      </div>
    </div>
    <?php if($settings['enable_device_checkout'] == 'true'): ?>
      <?php if($computer['Computer']['CanCheckout'] == 'true'): ?>
        <?php if($computer['Computer']['IsCheckedOut'] == 'true'): ?>
      <a href="<?php echo $this->Html->url("/checkout/requests?q=" . $computer['Computer']['ComputerName']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
        <i class="mdi mdi-cart-outline icon-sm text-white-50"></i> Checked Out
      </a>
        <?php else: ?>
      <a href="<?php echo $this->Html->url("/checkout/requests?q=" . $computer['Computer']['ComputerName']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
        <i class="mdi mdi-cart-remove icon-sm text-white-50"></i> Available
      </a>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif ?>
    <?php if(file_exists(WWW_ROOT . '/drivers/' . str_replace(' ','_',$computer['Computer']['Model']) . '.zip')): ?>
      <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url("/drivers/" . str_replace(' ','_',$computer['Computer']['Model']) . ".zip") ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2"><i class="mdi mdi-download icon-sm text-white-50"></i> Download Drivers</a>
    <?php else: ?>
      <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/uploadDrivers/' . $computer['Computer']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2 popup fancybox.ajax"><i class="mdi mdi-upload icon-sm text-white-50"></i> Upload Drivers</a>
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
          <?php echo $this->AttributeDisplay->drawTable($tables['general'], $validAttributes, $computer); ?>
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
          <?php echo $this->AttributeDisplay->drawTable($tables['hardware'], $validAttributes, $computer); ?>
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
          <?php echo $this->AttributeDisplay->drawTable($tables['network'], $validAttributes, $computer); ?>
        </div>
      </div>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <?php if(count($computer['LicenseKey']) > 0): ?>
  <div class="col-xl-7">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Licenses</h6>
      </div>
      <div class="card-body">
        <?php foreach($computer['LicenseKey'] as $aLicense): ?>
        <div class="row">
          <div class="col-md-4"><?php echo $aLicense['License']['LicenseName'] ?></div>
          <div class="col-md-6">
            <a href="javascript:;" onclick="copyLicense('<?php echo $aLicense['id'] ?>')" id="license_<?php echo $aLicense['id'] ?>" style="cursor: copy">
              <?php echo $aLicense['Keycode'] ?>
            </a>
            <div id="js-copy-alert-<?php echo $aLicense['id'] ?>" class="text-success" style="display:none" role="alert"></div>
          </div>
          <div class="col-md-2">
            <a href="<?php echo $this->Html->url('/manage/reset_license/' . $aLicense['ComputerLicense']['id']) ?>" class="text-danger">
              <i class="mdi mdi-delete icon-sm"></i>
            </a>
          </div>
        </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if($computer['Computer']['notes'] != ''): ?>
  <div class="col-xl-5">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php if($computer['Computer']['notes'] != ''): ?>
          <?php echo $this->Markdown->transform($computer['Computer']['notes']); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php if(count($computer['Applications']) > 0): ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><a href="#" onClick="return expandTable('applications')">Applications <i class="mdi mdi-chevron-down align-middle" id="applications-toggle"></i></a></h6>
      </div>
      <div class="card-body">
        <table id="applications" class="table table-striped" style="display:none">
          <?php foreach ($computer['Applications'] as $post): ?>
          <tr>
          <?php
              $row_class = '';

              if($post['monitoring'] == 'true')
              {
                $row_class = 'restricted';
              }
          ?>
          <td class="<?php echo $row_class ?>">
            <?php echo $this->Html->link( $post['full_name'], '/search/searchApplication/' . $post['id']); ?>
          </td>
          <td width="20%" class="<?php echo $row_class ?>" align="right">
            <?php if(key_exists($post['id'], $lifecycles)): ?>
            <a href="<?php echo $this->Html->url('/applications/lifecycle?q=' . $post['name']) ?>" title="Has Lifecycle" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"><i class="mdi mdi-update icon-sm icon-inline text-white-50"></i></a>
            <?php endif; ?>
            <a href="<?php echo $this->Html->url('/applications/unassign_application/' . $post['id'] . '/' . $computer['Computer']['id']) ?>" title="Unassign Application" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="mdi mdi-close icon-sm icon-inline text-white-50"></i></a>
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
            <td><?php echo $this->Html->link( $post['Service']['name'] , '/search/searchService/' . $post['Service']['name']); ?></td>
            <td><?php echo $post['Service']['startmode'] ?></td>
            <td><?php echo $post['Service']['status'] ?></td>
        </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
 </div>
 <?php endif ?>
