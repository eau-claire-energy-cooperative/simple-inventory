<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
<?=
//script to load the confirmation dialog
  $this->Html->scriptBlock("$(document).ready(function() {
    $('a.delete-lifecycle').confirm({
        title: 'Delete Lifecycle',
        content: 'Are you sure you want to delete this lifecycle?',
        buttons: {
            yes: function(){
                location.href = this.\$target.attr('href');
            },
            cancel: function(){

            }
        }
    })

    dataTable = $('#dataTable').DataTable({
      paging: false,
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
      search: {
        search: '" . $q . "'
      },
      columnDefs: [
        {'searchable': false, 'targets': [-1]},
        { targets: [0,1,-1], visible: true},
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
    });", ["block"=>true]);
?>
<div class="mb-4" align="right">
  <a href="<?= $this->Url->build(['controller' => 'applications', 'action' => 'add_lifecycle']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Create Lifecycle</a>
  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm" onClick="exportDataTableToCSV(dataTable, 'lifecycle.csv')"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>

</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <p>Software lifecycles can be used to keep track of upgrades. Here you can attach lifecycles to specific <?= $this->Html->link('applications','/applications') ?> and track when updates may be necessary.
      Do note that when a lifecycle is attached to an application that application can no longer be deleted from the system.</p>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Application Name</th>
        <th>Update Check Due</th>
        <th>Last Check Date</th>
        <th>Next Check Date</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($lifecycles as $post): ?>
        <?php $isDue = $this->Lifecycle->isDue($post['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $post['update_frequency']) ?>
        <tr>
          <td>
            <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/view_lifecycle/' . $post['application']['id']) ?>"><?= $post['application']['full_name'] ?></a>
          </td>
          <td data-sort="<?= ($isDue) ? "true" : "false" ?>">
            <?php if($isDue): ?>
            <p class="btn btn-sm btn-danger">Yes</p>
            <?php else: ?>
            <p class="btn btn-sm btn-success">No</p>
            <?php endif; ?>
          </td>
          <td>
            <?= $post['last_check']->i18nFormat("MM/dd/yyyy") ?>
          </td>
          <td>
            <?= $this->Time->format($this->Lifecycle->getNextDate($post['last_check']->i18nFormat("yyyy-MM-dd HH:mm:ss"), $post['update_frequency']), 'M/d/Y') ?>
          </td>
          <td align="right">
            <a href="<?= $this->Url->build('/applications/check_lifecycle/' . $post['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Update Last Check Date"><i class="mdi mdi-calendar icon-sm icon-inline text-white-50"></i></a>
            <a href="<?= $this->Url->build('/applications/edit_lifecycle/' . $post['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Edit Lifecycle"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i></a>
            <a href="<?= $this->Url->build('/applications/delete_lifecycle/' . $post['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-lifecycle" title="Delete Lifecycle" data-title="Delete Lifecycle"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
