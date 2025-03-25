<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
<?=
//script to load the confirmation dialog
  $this->Html->scriptBlock("$(document).ready(function() {
    $('a.delete-application').confirm({
      title: 'Delete Application',
      content: 'Are you sure you want to delete this application?',
      buttons: {
        yes: function(){
            location.href = this.\$target.attr('href');
        },
        cancel: function(){

        }
      }
    });

    dataTable = $('#dataTable').DataTable({
      paging: true,
      pageLength: 100,
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
        search: '" . $q . "'
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
    });", ["block"=>true]);
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build(['controller' => 'applications', 'action' => 'add_application']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Application</a>
  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm" onClick="exportTableToCSV('dataTable', 'applications.csv')"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Application Name</th>
        <th>Version</th>
        <th>Devices</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($applications as $post): ?>
          <tr>
            <td><?= $post['name'] ?></td>
            <td><?= $post['version'] ?></td>
            <td><?= $this->Html->link(count($post['computer']), '/search/searchApplication/' . $post['id']) ?></td>
            <td width="20%" align="right">
              <?php if(isset($post['lifecycle'])): ?>
              <a href="<?= $this->Url->build('/applications/lifecycle?q=' . $post['name']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2" title="Has Lifecycle"><i class="mdi mdi-update icon-sm icon-inline text-white-50"></i></a>
              <?php endif; ?>
              <a data-fancybox data-type="ajax" href="javascript:;" title="Assign Device" data-src="<?= $this->Url->build('/ajax/assign_application/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
              <a href="<?= $this->Url->build(['action' => 'delete_application', $post['id']]) ?>" title="Delete Application" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-application" data-title="Confirm delete application"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
     </table>
   </div>
</div>
