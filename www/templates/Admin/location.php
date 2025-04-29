<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
  //script to load the confirmation dialog
  $this->Html->scriptBlock("$(document).ready(function() {

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
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'addLocation']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Location</a>
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
          <td><?=  $post['location']; ?></td>
          <td><?= count($post['computer']) ?></td>
            <td align="right">
           	<?php if($post['is_default'] == 'false'): ?>
           	    <a href="<?= $this->Url->build(['action'=>'setDefaultLocation',$post['id']]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2 p-2"><i class="mdi mdi-star-outline icon-sm icon-inline text-white-50"></i></a>
           	<?php else: ?>
  					   <button class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"><i class="mdi mdi-star icon-sm icon-inline text-white-50"></i> Default</button>
  				  <?php endif; ?>
  				    <a href="<?= $this->Url->build(['action' => 'editLocation', $post['id']]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i> Edit</a>
  				    <a data-title="Delete Location" href="<?= $this->Url->build(['action' => 'deleteLocation', $post['id']]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Delete</a>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
</table>
