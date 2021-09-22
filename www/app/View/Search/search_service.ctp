<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', array('inline'=>false));

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: false,
          dom: '<\"top\"ifp>rt',
          language: {
            'search': 'Filter:'
            }
          });
     });", array("inline"=>false))
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->here . ".csv" ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download CSV</a>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><?php echo $q ?> Search</h6>
        </div>
        <div class="card-body">
          <table class="table table-striped" id="dataTable">
             <thead>
                <tr>
                    <th>Computer Name</th>
                    <th>Current User</th>
                    <th>Computer Location</th>
                    <th>Start Mode</th>
                    <th>Status</th>
                </tr>
             </thead>
             <?php foreach ($results as $post): ?>
              <?php if(isset($post['Computer']['ComputerName'])): ?>
                <tr>
                  <td width="20%"> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('controller'=>'inventory','action' => 'moreInfo', $post['Computer']['id'])); ?></td>
                  <td width="20%"> <?php echo $post['Computer']['CurrentUser']; ?></td>
                  <td width="20%"> <?php echo $locations[$post['Computer']['ComputerLocation']] ?></td>
                  <td width="20%"> <?php echo $post['Service']['startmode']; ?></td>
                  <td> <?php echo $post['Service']['status']; ?></td>
               <?php endif ?>
              <?php endforeach; ?>
          </table>
        </div>
    </div>
  </div>
</div>
