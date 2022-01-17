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
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($lifecycles as $post): ?>
        <tr>
          <td><a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/view_lifecycle/' . $post['Applications']['id']) ?>"><?php echo $post['Applications']['full_name'] ?></a>
          <td>
            <?php if($this->Lifecycle->isDue(date('Y-m-d', strtotime($post['Lifecycle']['last_check'])), $post['Lifecycle']['update_frequency'])): ?>
            <p class="btn btn-sm btn-danger">Yes</p>
            <?php else: ?>
            <p class="btn btn-sm btn-success">No</p>
            <?php endif; ?>
          </td>
          <td align="right">
            <a href="<?php echo $this->Html->url('/applications/edit_lifecycle/' . $post['Lifecycle']['id'])?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i></a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
