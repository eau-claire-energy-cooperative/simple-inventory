<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      dataTable = $('#dataTable').DataTable({
        paging: false,
        stateSave: true,
        stateDuration: 60,
        layout: {
          top2Start: 'info',
          top2End: {
            search: {}
          },
          topStart: null,
          topEnd: {
            paging: {
              type: 'simple_numbers'
            }
          },
          bottomStart: null,
          bottomEnd: {
            paging: {
              type: 'simple_numbers'
            }
          }
        },
        language: {
          search: 'Filter:',
          paginate: {
            next: 'Next',
            previous: 'Previous'
          }
        }
      });
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm" onClick="exportTableToCSV('dataTable', 'Service_<?= $export_name ?>.csv')"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
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
              <th>Computer Name</th>
              <th>Current User</th>
              <th>Computer Location</th>
              <th>Start Mode</th>
              <th>Status</th>
            </tr>
           </thead>
           <?php foreach ($results as $post): ?>
            <?php if(isset($post['computer']['ComputerName'])): ?>
              <tr>
                <td data-sort="<?= $post['computer']['device_type']['name'] ?>"><i class="mdi mdi-<?= $post['computer']['device_type']['icon'] ?>"></i></td>
                <td> <?= $this->Html->link( $post['computer']['ComputerName'] , ['controller'=>'inventory','action' => 'moreInfo', $post['computer']['id']]); ?></td>
                <td> <?= $post['computer']['CurrentUser']; ?></td>
                <td> <?= $locations[$post['computer']['ComputerLocation']] ?></td>
                <td> <?= $post['startmode']; ?></td>
                <td> <?= $post['status']; ?></td>
              </tr>
             <?php endif ?>
            <?php endforeach; ?>
          </table>
        </div>
    </div>
  </div>
</div>
