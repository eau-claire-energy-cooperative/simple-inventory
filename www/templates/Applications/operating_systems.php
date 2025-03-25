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
        search: {
          search: ''
        },
        columnDefs: [
          {'searchable': false, 'targets': [-1]}
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

<div class="card shadow mb-4">
  <div class="card-body">
    <p>Operating systems currently assigned to devices are listed here. You can assign an End of Life date to any individual operating system to keep track of when support for this OS will be ending. As the end date approaches an alert will be displayed and if the end date has past it will be flagged as well. </p>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Operating System</th>
        <th>Devices Assigned</th>
        <th>End of Life</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach (array_keys($allOs) as $os): ?>
        <?php $hasEol = array_key_exists($os, $definedOs) ?>
        <tr>
          <td><?php echo $os ?></a>
          <td><?php echo $this->Html->link($allOs[$os], '/search/search/2/' . $os) ?></td>
          <?php if($hasEol): ?>
            <td align="center" data-sort="<?php echo $definedOs[$os] ?>" class="<?php echo $this->OperatingSystem->eolCSS($definedOs[$os]) ?>">
              <?php echo $definedOs[$os]->format("m-d-Y") ?>
            </td>
          <?php else: ?>
            <td align="center">

            </td>
          <?php endif; ?>
          </td>
          <td>
          <?php if($hasEol): ?>
            <a href="<?php echo $this->Url->build('/applications/delete_os_eol/' . urlencode($os))?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm btn-danger" title="Remove EOL date"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
          <?php else: ?>
            <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Url->build('/ajax/assign_os_eol/' . urlencode($os))?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm btn-primary" title="Add End Of Life"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
          <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
