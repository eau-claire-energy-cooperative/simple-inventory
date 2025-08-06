<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {

    dataTable = $('#dataTable').DataTable({
      paging: true,
      pageLength: 50,
      stateSave: false,
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
        {'searchable': false, 'targets': [-1]},
        {'className': 'dt-left', 'targets': '_all'},
        {'className': 'dt-right', 'targets': [-1]}
      ],
      language: {
        search: 'Filter:',
        paginate: {
          next: 'Next',
          previous: 'Previous'
        }
      }
    });
    });", ["block"=>true]);
?>

<div class="row">
  <div class="col-xl-6 col-md-6 mb-4">
    <div class="row">
      <div class="col-lg-6">
        <div class="card border-left-success shadow h-500 py-1">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Device</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  <p class="text-gray-800"><?= $computer['ComputerName'] ?></a></p>
                </div>
              </div>
              <div class="col-auto">
                <i class="mdi mdi-<?= $computer['device_type']['icon'] ?> icon-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-6 mb-4" align="right">
    <a href="<?= $this->Url->build(sprintf("/inventory/moreInfo/%d", $computer['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-arrow-left icon-sm text-white-50"></i> Back</a>
  </div>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <p>A detailed history of the changes for this device are displayed below. Clicking on the details of each will display both a before and after summary of the edited fields.</p>

    <table class="table table-striped" id="dataTable">
      <thead>
        <th>Timestamp</th>
        <th>Attributes Updated</th>
        <th>User</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach($computer['computer_history'] as $item): ?>
        <tr>
          <td data-sort="<?= $item['updated_timestamp']->format('U') ?>"><?= $item['updated_timestamp']->format('m/d/Y H:ia') ?></td>
          <td><?= count($item['orig_as_json']) ?></td>
          <td><?= $item['user'] ?></td>
          <td align="right">
  				  <a href="" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-eye-outline icon-sm icon-inline text-white-50"></i> Details</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
