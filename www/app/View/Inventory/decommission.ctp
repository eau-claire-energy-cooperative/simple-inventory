<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', false);

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: true,
          pageLength: 50,
          dom: '<\"top\"ifp>rt'
          });
     });", array("inline"=>false))
?>

		<table id="dataTable" class="table table-striped">
	<thead>

        <th>Device Name</th>
        <th>Redeployed As</th>
        <th>Wiped Hard Drive</th>
        <th>Recycled</th>
        <th>Decom Date</th>


	</thead>
	<tbody>
    <!-- Here is where we loop through our $posts array, printing out post info -->

    <?php foreach ($decommission as $post): ?>
    <tr>
    <td> <?php echo $this->Html->link( $post['Decommissioned']['ComputerName'] , array('action' => 'moreInfoDecommissioned', $post['Decommissioned']['id'])); ?></td>
    <td><?php echo $post['Decommissioned']['RedeployedAs']; ?></td>
    <td><?php echo $post['Decommissioned']['WipedHD']; ?>
     	<div style="float:right; ">
     	    <a href="<?php echo $this->Html->url(array( 'action' => 'changeWipeStatus',$post['Decommissioned']['id'], 'Yes')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
          <a href="<?php echo $this->Html->url(array('action' => 'changeWipeStatus',$post['Decommissioned']['id'],'No')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
     	</div></td>
       <td><?php echo $post['Decommissioned']['Recycled']; ?>
       <div style="float:right; ">
         <a href="<?php echo $this->Html->url(array( 'action' => 'changeRecycledStatus',$post['Decommissioned']['id'], 'Yes')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
         <a href="<?php echo $this->Html->url(array('action' => 'changeRecycledStatus',$post['Decommissioned']['id'],'No')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
     	</div>
      </td>
      <td data-sort="<?php echo $this->Time->fromstring($post['Decommissioned']['LastUpdated']) ?>"><?php echo $this->Time->format($post['Decommissioned']['LastUpdated'], '%m/%d/%Y');?></td>
    </tr>
         <?php endforeach; ?>

</tbody>
</table>
