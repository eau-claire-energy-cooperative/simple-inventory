<?php
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    echo $this->Html->css('dataTables.bootstrap4.min', false);

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('a.delete-license').confirm({
              title: 'Delete License',
              content: 'Are you sure you want to delete this license?',
              buttons: {
                  yes: function(){
                      location.href = this.\$target.attr('href');
                  },
                  cancel: function(){

                  }
              }
          });

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
  <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/new_license') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add License</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <?php if(count($licenses) == 0): ?>
    <p align="center">Add license keys for programs that can be assigned to devices and also moved when needed. Click <b>Add License</b> above to get started.</p>
    <?php endif; ?>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Assigned Device</th>
        <th>Application</th>
        <th>Key</th>
        <th></th>
      </thead>
    	<?php foreach($licenses as $aLicense): ?>
    	<tr>
    		<td width="20%"><?php if($aLicense['License']['comp_id'] != 0): ?>
    		    <?php echo $this->Html->link($aLicense['Computer']['ComputerName'], '/inventory/moreInfo/' . $aLicense['License']['comp_id']) ?>
    		  <?php else: ?>
            <span class="text-danger">UNASSIGNED</span>
    		  <?php endif; ?>
    		</td>
    		<td width="25%"><?php echo $aLicense['License']['ProgramName'] ?></td>
    		<td><?php echo $aLicense['License']['LicenseKey'] ?></td>
    		<td width="12%" align="right">
    		  <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/move_license/' . $aLicense['License']['id'] . '/' . $aLicense['License']['comp_id']) ?>" title="Move License"><i class="mdi mdi-arrow-all icon-inline mr-1"></i></a>
    		  <a href="<?php echo $this->Html->url(array('action' => 'deleteLicense', $aLicense['License']['id'])) ?>" class="text-danger delete-license" data-title="Confirm delete license"><i class="mdi mdi-delete icon-inline mr-2" title="Delete License"></i></a>
    		</td>
    	</tr>
    	<?php endforeach ?>
    </table>
  </div>
</div>
