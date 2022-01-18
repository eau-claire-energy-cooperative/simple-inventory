<?php
  echo $this->Html->script("jquery-confirm.min.js",false);
  echo $this->Html->script("jquery.dataTables.min.js", false);
  echo $this->Html->script("dataTables.bootstrap4.min.js", false);

  echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
  echo $this->Html->css('dataTables.bootstrap4.min', false);

  //script to load the confirm dialog
  echo $this->Html->scriptBlock("$(document).ready(function() {
    $('a.delete-lifecycle').confirm({
        title: 'Delete Lifecycle',
        content: 'Are you sure you want to delete this lifecycle?',
        buttons: {
            yes: function(){
                location.href = this.\$target.attr('href');
            },
            cancel: function(){

            }
        }
    })

    $('#dataTable').DataTable({
      stateSave: true,
      dom: '<\"top\"f>rt',
      search: {
        search: '" . $this->params['url']['q'] . "'
      },
      columnDefs: [
        {'searchable': false, 'targets': [-1]}
      ]
    })});", array("inline"=>false));
?>
<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url(array('controller' => 'applications', 'action' => 'add_lifecycle')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Create Lifecycle</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <p>Software lifecycles can be used to keep track of upgrades. Here you can attach lifecycles to specific <?php echo $this->Html->link('applications','/applications') ?> and track when updates may be necessary.
      Do note that when a lifecycle is attached to an application that application can no longer be deleted from the system.</p>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Application Name</th>
        <th>Update Needed</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($lifecycles as $post): ?>
        <?php $isDue = $this->Lifecycle->isDue(date('Y-m-d', strtotime($post['Lifecycle']['last_check'])), $post['Lifecycle']['update_frequency']) ?>
        <tr>
          <td><a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/view_lifecycle/' . $post['Applications']['id']) ?>"><?php echo $post['Applications']['full_name'] ?></a>
          <td data-sort="<?php echo ($isDue) ? "true" : "false" ?>">
            <?php if($isDue): ?>
            <p class="btn btn-sm btn-danger">Yes</p>
            <?php else: ?>
            <p class="btn btn-sm btn-success">No</p>
            <?php endif; ?>
          </td>
          <td align="right">
            <a href="<?php echo $this->Html->url('/applications/check_lifecycle/' . $post['Lifecycle']['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Update Last Check Date"><i class="mdi mdi-calendar icon-sm icon-inlin text-white-50"></i></a>
            <a href="<?php echo $this->Html->url('/applications/edit_lifecycle/' . $post['Lifecycle']['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Edit Lifecycle"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i></a>
            <a href="<?php echo $this->Html->url('/applications/delete_lifecycle/' . $post['Lifecycle']['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-lifecycle" title="Delete Lifecycle" data-title="Delete Lifecycle"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
