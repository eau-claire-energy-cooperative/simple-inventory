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
        <th>End of Life</th>
      </thead>
      <tbody>
        <?php foreach (array_keys($allOs) as $os): ?>
        <?php $hasEol = array_key_exists($os, $definedOs) ?>
        <tr>
          <td><?php echo $os ?></a>
          <td><?php echo $this->Html->link($allOs[$os], '/search/search/2/' . $os) ?></td>
          <?php if($hasEol): ?>
            <td align="center" data-sort="<?php echo $definedOs[$os] ?>">
              <?php if($this->Time->isPast($definedOs[$os])): ?>
              <i class="mdi mdi-calendar-alert text-danger icon-inline"></i>
              <?php elseif($this->Time->isThisYear($definedOs[$os])): ?>
              <i class="mdi mdi-calendar-alert text-warning icon-inline"></i>
              <?php endif; ?>
              <?php echo $this->Time->format($definedOs[$os], "%m-%d-%Y") ?>
              <a href="<?php echo $this->Html->url('/applications/delete_os_eol/' . urlencode($os))?>" class="text-danger ml-1" title="Remove EOL date"><i class="mdi mdi-delete icon-sm icon-inline"></i></a>
            </td>
          <?php else: ?>
            <td align="center">
              <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/assign_os_eol/' . urlencode($os))?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm btn-primary" title="Add End Of Life"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
            </td>
          <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
