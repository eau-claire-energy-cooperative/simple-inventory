<?= $this->Html->css('jquery-confirm.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery-confirm.min.js"], ['block'=>'script']) ?>
<?=
  //script to load the datatable
  $this->Html->scriptBlock("$(document).ready(function() {
    $('a.delete-decom').confirm({
      title: 'Permanently Delete Device',
      content: 'This will delete this device and all history of it. Are you sure?',
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

<div class="mb-2" align="right">
  <a href="<?php echo $this->Url->build(array('action' => 'deleteDecom', $decommissioned['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-decom"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Permanent Delete</a>
</div>
<div class="row">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Wiped Hard Drive</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['WipedHD']?></div>
          </div>
          <div class="col-auto">
            <i class="mdi mdi-harddisk-remove icon-2x icon-inline text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Recycled</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['Recycled']?></div>
          </div>
          <div class="col-auto">
            <i class="mdi mdi-recycle icon-2x icon-inline text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if(!empty($decommissioned['RedeployedAs'])): ?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Redeployed As</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['RedeployedAs'];?></div>
          </div>
          <div class="col-auto">
            <i class="mdi mdi-monitor icon-2x icon-inline text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-secondary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Decommission Date</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['LastUpdated']->format('m/d/Y') ?></div>
          </div>
          <div class="col-auto">
            <i class="mdi mdi-calendar icon-2x icon-inline text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <table class="table table-striped">
              <tr>
                <th style="width: 200px;">Device Name</th>
                <th style="width: 250px;">Location</th>
                <th style="width: 250px;">Current User</th>
                <th style="width: 250px;">Serial Number</th>
                <th style="width: 250px;">Asset ID</th>
              </tr>
              <tr>
                  <td><?php echo $decommissioned['ComputerName']?></td>
                  <td><?php echo $decommissioned['location']['location']; ?></td>
                  <td><?php echo $decommissioned['CurrentUser']?></td>
                  <td><?php echo $decommissioned['SerialNumber']?></td>
                  <td><?php echo $decommissioned['AssetId']?> </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Hardware Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <table class="table table-striped">
            <tr>
                <th style="width: 200px;">Manufacturer</th>
                <th style="width: 200px;">Model</th>
                <th style="width: 250px;">Operating System</th>
                <th style="width: 250px;">CPU</th>
                <th style="width: 250px;">Memory</th>
            </tr>
            <tr>
                <td> <?php echo $decommissioned['Manufacturer']; ?></td>
                <td> <?php echo $decommissioned['Model']; ?></td>
                <td><?php echo $decommissioned['OS']; ?></td>
                <td><?php echo $decommissioned['CPU']?></td>
                <td> <?php echo $decommissioned['Memory'] . " GB"; ?></td>
            </tr>
            <tr>
                <th style="width: 250px;">Number of Monitors</th>
            </tr>
            <tr>
              <td> <?php echo $decommissioned['NumberOfMonitors']; ?></td>
            </tr>
          </table>
          </div>
        </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card border-left-info shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Network Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <table class="table table-striped">
            <tr>
                <th style="width: 200px;">IP Address</th>
                <th style="width: 250px;">MAC Address</th>
                <th style="width: 250px;"></th>
                <th style="width: 250px;"></th>
                 <th style="width: 250px;"></th>
            </tr>
              <tr>
                <td><?php echo $decommissioned['IPaddress']?></td>
                 <td><?php echo $decommissioned['MACaddress']?></td>
                 <td></td>
                  <td></td>
                  <td></td>
               </tr>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-5">
    <div class="card border-left-dark shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
      </div>
      <div class="card-body">
        <?php echo $this->Markdown->transform($decommissioned['notes']); ?>
      </div>
    </div>
  </div>
</div>
