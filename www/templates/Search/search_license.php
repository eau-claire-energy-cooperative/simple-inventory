<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      $('#dataTable').DataTable({
        paging: false,
        dom: '<\"top\"ifp>rt',
        language: {
          'search': 'Filter:'
          }
        });
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/manage/view_license/' . $license_id) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="mdi mdi-certificate icon-sm icon-inline text-white-50"></i> License Info</a>
  <a href=".csv" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><?= $q ?> Search</h6>
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
               <?php foreach($license['computer'] as $computer): ?>
                 <?php if(isset($computer['ComputerName'])): ?>
              <tr>
                  <td data-sort="<?= $computer['device_type']['name'] ?>"><i class="mdi mdi-<?= $computer['device_type']['icon'] ?>"></i></td>
                  <td> <?= $this->Html->link( $computer['ComputerName'] , ['controller'=>'inventory','action' => 'moreInfo', $computer['id']]); ?></td>
                  <td><?= $license['Keycode'] ?></td>
                  <td> <?= $locations[$computer['ComputerLocation']] ?></td>
                  <td> <?= $computer['LastUpdated']; ?></td>
                  <td>
                    <a href="<?= $this->Url->build(sprintf('/manage/reset_license/%d/%d', $computer['id'], $license['id'])) ?>" class="text-danger">
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
