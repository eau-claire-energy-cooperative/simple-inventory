<div class="row">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Wiped Hard Drive</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['Decommissioned']['WipedHD']?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-eraser fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Earnings (Monthly) Card Example -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Recycled</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['Decommissioned']['Recycled']?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-recycle fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if(!empty($decommissioned['Decommissioned']['RedeployedAs'])): ?>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Redeployed As</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $decommissioned['Decommissioned']['RedeployedAs'];?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-desktop fa-2x text-gray-300"></i>
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
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Time->format($decommissioned['Decommissioned']['LastUpdated'], '%m/%d/%Y');?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                  <td><?php echo $decommissioned['Decommissioned']['ComputerName']?></td>
                  <td><?php echo $decommissioned['Location']['location']; ?></td>
                  <td><?php echo $decommissioned['Decommissioned']['CurrentUser']?></td>
                  <td><?php echo $decommissioned['Decommissioned']['SerialNumber']?></td>
                  <td><?php echo $decommissioned['Decommissioned']['AssetId']?> </td>
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
                <td> <?php echo $decommissioned['Decommissioned']['Manufacturer']; ?></td>
                <td> <?php echo $decommissioned['Decommissioned']['Model']; ?></td>
                <td><?php echo $decommissioned['Decommissioned']['OS']; ?></td> <!--  $comparisonID,$columnID,$modelID,$nameID -->
                <td><?php echo $decommissioned['Decommissioned']['CPU']?></td>
                <td> <?php echo $decommissioned['Decommissioned']['Memory'] . " GB"; ?></td>
            </tr>
            <tr>
                <th style="width: 250px;">Number of Monitors</th>
            </tr>
            <tr>
              <td> <?php echo $decommissioned['Decommissioned']['NumberOfMonitors']; ?></td>
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
                <td><?php echo $decommissioned['Decommissioned']['IPaddress']?></td>
                 <td><?php echo $decommissioned['Decommissioned']['MACaddress']?></td>
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
        <?php echo $decommissioned['Decommissioned']['notes'];?>
      </div>
    </div>
  </div>
</div>
