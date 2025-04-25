<script type="text/javascript">
var dataTable = null;
</script>
<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      dataTable = $('#dataTable').DataTable({
        paging: true,
        pageLength: 50,
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
        columnDefs: [
          { targets: [". $this->DynamicTable->listVisibleColumns(array_keys($columnNames), $displayAttributes, [0,1,-1,-2], 2) . "], visible: true},
          { targets: '_all', visible: false }
        ],
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
  <a href="<?= $this->Url->build('/inventory/add') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Device</a>
  <a href="<?= $this->Url->build('/inventory/import') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-upload icon-sm icon-inline text-white-50"></i> Import Devices</a>
  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm" onClick="exportDataTableToCSV(dataTable, 'inventory.csv')"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <table class="table table-striped" id="dataTable">
    	<thead>
        <tr>
            <th><i class="mdi mdi-monitor-cellphone-star"></i></th>
            <th>Device Name</th>
            <?php foreach(array_keys($columnNames) as $attribute): ?>
            <th><?= $columnNames[$attribute] ?></th>
            <?php endforeach; ?>
            <th>Location</th>
            <th>Last Update</th>
        </tr>
    	</thead>
    	<tbody>
        <?php foreach ($computer as $post): ?>
        <tr>
            <td data-sort="<?= $post['device_type']['name'] ?>">
              <a href="<?= $this->Url->build('/search/search/5/' . $post['device_type']['name']) ?>" class="icon-link"><i class="mdi mdi-<?= $post['device_type']['icon'] ?>"></i></a>
            </td>
            <td> <?= $this->Html->link( $post['ComputerName'] , ['action' => 'moreInfo', $post['id']]); ?></td>
            <?php foreach(array_keys($columnNames) as $attribute): ?>
            <td><?= $post[$attribute] ?></td>
            <?php endforeach; ?>
            <td><?= $this->Html->link( $post['location']['location'], ['controller'=>'search','action' => 'search', 0, $post['ComputerLocation']]); ?></td>
            <td data-sort="<?= $post['LastUpdated']->format('U') ?>"><?= $post['LastUpdated']->format('m/d/Y') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</div>
