<?php 
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    
    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {
  
        $('a.delete-location').confirm({
          content: 'Are you sure you want to delete this location?',
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
  <a href="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addLocation')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50"></i> Add Location</a>
</div>
 <table class="table table-striped">
    <thead>
      <th>Name</th>
      <th>Assets Assigned</th>
      <th></th>
    </thead>
    <tbody>
      <?php foreach ($location as $post): ?>
      <tr>
          <td><?php echo  $post['Location']['location']; ?></td>
          <td><?php echo count($post['Computer']) ?></td>
            <td align="right">
           	<?php if($post['Location']['is_default'] == 'false'): ?>
           	    <a href="<?php echo $this->Html->url(array('action'=>'setDefaultLocation',$post['Location']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">Set Default</a>
           	<?php else: ?>
  					   <span class="btn btn-success btn-sm shadow-sm">Default</span>	
  				  <?php endif; ?>
  				    <a href="<?php echo $this->Html->url(array('action' => 'editLocation', $post['Location']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
  				    <a href="<?php echo $this->Html->url(array('action' => 'deleteLocation', $post['Location']['id'])) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
</table>
