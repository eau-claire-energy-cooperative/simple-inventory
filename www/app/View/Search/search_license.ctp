<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', array('inline'=>false));

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: false,
          dom: '<\"top\"ifp>rt',
          language: {
            'search': 'Filter:'
            }
          });
     });", array("inline"=>false))
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/manage/view_license/' . $license_id) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="mdi mdi-certificate icon-sm icon-inline text-white-50"></i> License Info</a>
  <a href="<?php echo $this->here . ".csv" ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><?php echo $q ?> Search</h6>
        </div>
        <div class="card-body">
          <table class="table table-striped" id="dataTable">
             <thead>
                <tr>
                    <th><i class="mdi mdi-monitor-cellphone-star"></i></th>
                    <th>Device Name</th>
                    <th>License Key</th>
                    <th>Location</th>
                    <th>Last Update</th>
                    <th></th>
                </tr>
             </thead>
             <?php foreach ($results as $license): ?>
               <?php foreach($license['Computer'] as $computer): ?>
                 <?php // keycode can be in one of two places
                  if(isset($license['LicenseKey'])){
                   $keycode = $license['LicenseKey']['Keycode'];
                  } else{
                   $keycode = $license['Keycode'];
                  }
                  ?>
                 <?php if(isset($computer['ComputerName'])): ?>
              <tr>
                  <td data-sort="<?php echo $computer['DeviceType']['name'] ?>"><i class="mdi mdi-<?php echo $computer['DeviceType']['icon'] ?>"></i></td>
                  <td> <?php echo $this->Html->link( $computer['ComputerName'] , array('controller'=>'inventory','action' => 'moreInfo', $computer['id'])); ?></td>
                  <td><?php echo $keycode ?></td>
                  <td> <?php echo $locations[$computer['ComputerLocation']] ?></td>
                  <td> <?php echo $computer['LastUpdated']; ?></td>
                  <td>
                    <a href="<?php echo $this->Html->url('/manage/reset_license/' . $computer['ComputerLicense']['id']) ?>" class="text-danger">
                      <i class="mdi mdi-delete icon-sm"></i>
                    </a>
                  </td>
              </tr>
                  <?php endif ?>
                <?php endforeach; ?>
              <?php endforeach; ?>
          </table>
        </div>
    </div>
  </div>
</div>
