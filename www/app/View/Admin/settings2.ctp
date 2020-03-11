<?php 
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    
    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {
  
        $('a.delete-setting').confirm({
          content: 'Are you sure you want to delete this setting?',
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
  <a href="<?php echo $this->Html->url('/admin/edit_setting') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add Setting</a>
</div>
<p>Please note, you should not add or remove settings from this page unless you know what you are doing. They will affect, and possibly break, how the website and inventory update functions run. </p>

<table class="table table-striped">
	<?php foreach($settings_list as $aSetting): ?>
	<tr>
		<td><?php echo $aSetting['Setting']['key'] ?></td>
		<td style="word-wrap: break-word;min-width: 160px;max-width: 500px;"><?php echo $aSetting['Setting']['value'] ?></td>
		<td>
		  <a href="<?php echo $this->Html->url("/admin/edit_setting/". $aSetting['Setting']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
      <a data-title="Delete Setting" href="<?php echo $this->Html->url("/admin/settings/delete?id=". $aSetting['Setting']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-setting"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>  
		</td>
	</tr>
	<?php endforeach; ?>
</table>
