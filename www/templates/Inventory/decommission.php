<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      dataTable = $('#dataTable').DataTable({
        paging: true,
        pageLength: 50,
        stateSave: true,
        stateDuration: 60,
        order: [
          [4, 'desc'],
          [0, 'desc']
        ],
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
    <?php foreach ($decommission as $post): ?>
    <tr>
    <td><?= $this->Html->link( $post['ComputerName'] , ['action' => 'moreInfoDecommissioned', $post['id']]); ?></td>
    <td><?= $post['RedeployedAs']; ?></td>
    <td><?= $post['WipedHD']; ?>
     	<div style="float:right; ">
          <?php if($post['WipedHD'] == 'No' || empty($post['WipedHD'])): ?>
     	    <a href="<?= $this->Url->build(['action' => 'changeDecomStatus',$post['id'], 'hd']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
          <?php else: ?>
          <a href="<?= $this->Url->build(['action' => 'changeDecomStatus',$post['id'], 'hd']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
          <?php endif; ?>
     	</div></td>
       <td><?= $post['Recycled']; ?>
       <div style="float:right; ">
        <?php if($post['Recycled'] == 'No' || empty($post['WipedHD'])): ?>
        <a href="<?= $this->Url->build(['action' => 'changeDecomStatus',$post['id'], 'recycle']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="mdi mdi-check icon-sm text-white-50"></i></a>
        <?php else: ?>
        <a href="<?= $this->Url->build(['action' => 'changeDecomStatus',$post['id'], 'recycle']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1"><i class="mdi mdi-close icon-sm text-white-50"></i></a>
        <?php endif; ?>
     	</div>
      </td>
      <td data-sort="<?= $post['LastUpdated']->format('U') ?>"><?= $post['LastUpdated']->format('m/d/Y')?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
