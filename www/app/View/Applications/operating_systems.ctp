<?php
  echo $this->Html->script("jquery.dataTables.min.js", false);
  echo $this->Html->script("dataTables.bootstrap4.min.js", false);

  echo $this->Html->css('dataTables.bootstrap4.min', false);

  //script to load the confirm dialog
  echo $this->Html->scriptBlock("$(document).ready(function() {
    $('#dataTable').DataTable({
      dom: '<\"top\"f>rt',
      paging: false,
      search: {
        search: '" . $this->params['url']['q'] . "'
      },
      columnDefs: [
        {'searchable': false, 'targets': [-1]}
      ]
    })});", array("inline"=>false));
?>

<div class="card shadow mb-4">
  <div class="card-body">
    <p>Operating systems currently assigned to devices are listed here. </p>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Operating System</th>
        <th>Devices Assigned</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach (array_keys($allOs) as $os): ?>
        <tr>
          <td><?php echo $os ?></a>
          <td><?php echo $this->Html->link($allOs[$os], '/search/search/2/' . $os) ?></td>
          <td align="right">
            <!--- <a href="<?php echo $this->Html->url('/applications/operating_systems')?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm btn-primary" title="Add End Of Life"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a> -->
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
