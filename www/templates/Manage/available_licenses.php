<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->css('jquery-confirm.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", 'jquery-confirm.min.js'], ['block'=>'script']) ?>
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
        language: {
          search: 'Filter:',
          paginate: {
            next: 'Next',
            previous: 'Previous'
          }
        }
      })

      $('a.assign-license-key').confirm({
          title: 'Assign Key',
          content: 'Are you sure you want to assign this key to this device?',
          buttons: {
              yes: function(){
                  location.href = this.\$target.attr('href');
              },
              cancel: function(){

              }
          }
      })
   });", ["block"=>true])
?>
<div class="card shadow mb-4">
  <div class="card-body">
    <h3>Assigning to: <b><?= $computer['ComputerName'] ?></b></h3>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>License Name</th>
        <th>Key</th>
        <th># Available</th>
        <th></th>
      </thead>
    	<?php foreach($available_keys as $key): ?>
    	<tr>
    		<td><?= $key['license']['LicenseName'] ?></td>
    		<td><?= $key['Keycode'] ?></td>
        <td><?= $key['Quantity'] - count($key['computer']) ?>/<?= $key['Quantity'] ?></td>
        <td><a href="<?= $this->Url->build(sprintf('/manage/assign_license_key/%d/%d', $key['id'], $computer['id'])) ?>" title="Assign <?= $key['license']['LicenseName'] ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary assign-license-key"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a></td>
    	</tr>
      <?php endforeach ?>
    </table>
  </div>
</div>
