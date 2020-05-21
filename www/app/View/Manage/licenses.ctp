<?php 
    echo $this->Html->script("jquery-confirm.min.js",false);

    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    
    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('a.delete-license').confirm({
              content: 'Are you sure you want to delete this license?',
              buttons: {
                  yes: function(){
                      location.href = this.\$target.attr('href');
                  },
                  cancel: function(){
                    
                  }
              }
          });
     });", array("inline"=>false)) 
?>

<div class="mb-4" align="right">
  <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/new_license') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add License</a>
</div>

<table class="table table-striped">
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
		  <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/move_license/' . $aLicense['License']['id'] . '/' . $aLicense['License']['comp_id']) ?>" title="Move License"><i class="fas fa-arrows-alt mr-1"></i></a>
		  <a href="<?php echo $this->Html->url(array('action' => 'deleteLicense', $aLicense['License']['id'])) ?>" class="text-danger delete-license" data-title="Confirm delete license"><i class="fas fa-trash mr-2" title="Delete License"></i></a>
		</td>
	</tr>
	<?php endforeach ?>
</table>