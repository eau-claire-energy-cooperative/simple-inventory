<?php
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));

    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {

        $('a.delete-location').confirm({
          content: 'Are you sure you want to delete this type?',
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
  <a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addDeviceType')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add Type</a>
</div>
 <table class="table table-striped">
    <thead>
      <th>Name</th>
      <th>Assets Assigned</th>
      <th></th>
    </thead>
    <tbody>
      <?php foreach ($device_types as $post): ?>
      <tr>
          <td><?php echo  $post['DeviceType']['name']; ?></td>
          <td><?php echo count($post['Computer']) ?></td>
            <td align="right">
  				    <a href="<?php echo $this->Html->url(array('action' => 'editDeviceType', $post['DeviceType']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
  				    <a data-title="Delete Type" href="<?php echo $this->Html->url(array('action' => 'deleteDeviceType', $post['DeviceType']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
</table>
