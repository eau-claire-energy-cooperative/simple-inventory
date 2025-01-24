<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
$this->Html->scriptBlock("$(document).ready(function() {
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
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/admin/edit_setting') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50"></i> Add Setting</a>
</div>
<p>Please note, you should not add or remove settings from this page unless you know what you are doing. They will affect, and possibly break, how the website and inventory update functions run. </p>

<table class="table table-striped">
	<?php foreach($settings_list as $aSetting): ?>
	<tr>
		<td><?= $aSetting['key'] ?></td>
		<td style="word-wrap: break-word;min-width: 160px;max-width: 500px;"><?= $aSetting['value'] ?></td>
		<td>
		  <a href="<?= $this->Url->build("/admin/edit_setting/". $aSetting['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50"></i> Edit</a>
      <a data-title="Delete Setting" href="<?= $this->Url->build("/admin/settings2/delete?id=". $aSetting['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-setting"><i class="material-icons mi-sm mi-inline text-white-50"></i> Delete</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
