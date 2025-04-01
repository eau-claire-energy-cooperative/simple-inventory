<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js", "csv_export.js"], ['block'=>'script']) ?>
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
      });
   });", ["block"=>true])
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
    <td> <?php echo $this->Html->link( $post['ComputerName'] , array('action' => 'moreInfoDecommissioned', $post['id'])); ?></td>
    <td><?php echo $post['RedeployedAs']; ?></td>
    <td><?php echo $post['WipedHD']; ?>
     	<div style="float:right; ">
     	    <a href="<?php echo $this->Url->build(array( 'action' => 'changeWipeStatus',$post['id'], 'Yes')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
          <a href="<?php echo $this->Url->build(array('action' => 'changeWipeStatus',$post['id'],'No')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
     	</div></td>
       <td><?php echo $post['Recycled']; ?>
       <div style="float:right; ">
         <a href="<?php echo $this->Url->build(array( 'action' => 'changeRecycledStatus',$post['id'], 'Yes')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
         <a href="<?php echo $this->Url->build(array('action' => 'changeRecycledStatus',$post['id'],'No')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
     	</div>
      </td>
      <td data-sort="<?php echo $post['LastUpdated']->format('U') ?>"><?php echo $post['LastUpdated']->format('m/d/Y')?></td>
    </tr>
         <?php endforeach; ?>

</tbody>
</table>
