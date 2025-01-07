<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      $('#dataTable').DataTable({
        paging: true,
        pageLength: 50,
        stateSave: true,
        dom: '<\"top\"ifp>rt<\"bottom\"p>',
        language: {
          'search': 'Filter:'
          }
        });
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/inventory/add') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Device</a>
  <a href="<?= $this->Url->build('/inventory/import') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-upload icon-sm icon-inline text-white-50"></i> Import Devices</a>
  <a href="<?= $this->Url->build('/search/listAll.csv') ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="card shadow mb-4">
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
    	<tbody>
        <?php foreach ($computer as $post): ?>
        <tr>
            <td data-sort="<?= $post['device_type']['name'] ?>">
              <a href="<?= $this->Url->build('/search/search/5/' . $post['device_type']['name']) ?>" class="icon-link"><i class="mdi mdi-<?= $post['device_type']['icon'] ?>"></i></a>
            </td>
            <td> <?= $this->Html->link( $post['ComputerName'] , ['action' => 'moreInfo', $post['id']]); ?></td>
            <?php foreach($displayAttributes as $attribute): ?>
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
