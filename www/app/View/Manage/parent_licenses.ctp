<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', false);

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: true,
          pageLength: 100,
          dom: '<\"top\"ifp>rt',
          columnDefs: [
            {'searchable': false, 'targets': [-1]}
          ]
       });
     });", array("inline"=>false))
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/manage/edit_license') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add License</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <?php if(count($licenses) == 0): ?>
    <p align="center">Add licenses for software you need to track. Click <b>Add License</b> above to get started.</p>
    <?php endif; ?>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>License Name</th>
        <th>Vendor</th>
        <th>Expiration</th>
      </thead>
    	<?php foreach($licenses as $aLicense): ?>
      <?php $sort_date = ($aLicense['License']['ExpirationDate'] != '') ? $this->Time->fromstring($aLicense['License']['ExpirationDate']) : 0 ?>
    	<tr>
    		<td><?php echo $this->Html->link($aLicense['License']['LicenseName'], '/manage/view_license/' . $aLicense['License']['id']) ?></td>
    		<td><?php echo $aLicense['License']['Vendor'] ?></td>
    		<td data-sort="<?php echo $sort_date ?>" class="<?php echo ($this->Time->isPast($aLicense['License']['ExpirationDate'])) ? 'text-danger': ''?>">
          <?php echo $this->Time->format($aLicense['License']['ExpirationDate'], '%m/%d/%Y') ?>
        </td>
    	</tr>
    	<?php endforeach ?>
    </table>
  </div>
</div>
