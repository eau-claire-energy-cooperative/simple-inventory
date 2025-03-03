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
                    <?php foreach($displayAttributes as $attribute): ?>
                    <th><?= $columnNames[$attribute] ?></th>
                    <?php endforeach; ?>
                    <th>Location</th>
                    <th>Last Update</th>
                </tr>
             </thead>
             <?php foreach ($results as $post): ?>
              <?php if(isset($post['ComputerName'])): ?>
              <tr>
                  <td data-sort="<?= $post['device_type']['name'] ?>"><i class="mdi mdi-<?= $post['device_type']['icon'] ?>"></i></td>
                  <td> <?= $this->Html->link( $post['ComputerName'] , ['controller'=>'inventory','action' => 'moreInfo', $post['id']]); ?></td>
                  <?php foreach($displayAttributes as $attribute): ?>
                  <td><?= $post[$attribute] ?></td>
                  <?php endforeach; ?>
                  <td> <?= $locations[$post['ComputerLocation']] ?></td>
                  <td> <?= $post['LastUpdated']; ?></td>
              </tr>
               <?php endif ?>
              <?php endforeach; ?>
          </table>
        </div>
    </div>
  </div>
</div>
