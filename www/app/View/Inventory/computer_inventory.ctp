<?php 
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);
    
    echo $this->Html->css('dataTables.bootstrap4.min', array('inline'=>false));
    
    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: false, 
          dom: '<\"top\"ifp>rt'
          });
     });", array("inline"=>false)) 
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/inventory/add') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add Computer</a>
  <a href="<?php echo $this->Html->url('/search/listAll.csv') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download CSV</a>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
  </div>
  <div class="card-body">
    <table class="table table-striped" id="dataTable">
    	<thead>
        <tr>
            <th>Computer Name</th>
            <th>Current User</th>
            <th>Operating System</th>	
            <th>Memory</th>
            <th>Model</th>
            <th>Location</th>
            <th>Last Update</th>
        </tr>
    	</thead>
    	<tbody>
        
    
        <?php foreach ($computer as $post): ?>
        <tr>
            <td> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('action' => 'moreInfo', $post['Computer']['id'])); ?></td>
             <td><?php echo $post['Computer']['CurrentUser']; ?></td>
             <td><?php echo $post['Computer']['OS']; ?></td>
             <td><?php echo $post['Computer']['Memory']  ?> GB</td>
             <td><?php echo $post['Computer']['Model'] ?></td>
             <td><?php echo $this->Html->link( $post['Location']['location'], array('controller'=>'search','action' => 'search', 0,$post['Computer']['ComputerLocation'])); ?></td>
             <td><?php echo $this->Time->format('m/d/Y',$post['Computer']['LastUpdated']) ?></td>   
        </tr>
        
        <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</div>