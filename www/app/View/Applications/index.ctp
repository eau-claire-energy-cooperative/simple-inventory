<?php
    echo $this->Html->script("jquery-confirm.min.js",false);
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
    echo $this->Html->css('dataTables.bootstrap4.min', false);

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
      $('a.delete-application').confirm({
          title: 'Delete Application',
          content: 'Are you sure you want to delete this application?',
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
        search: {
          search: '" . $this->params['url']['q'] . "'
        },
        columnDefs: [
          {'searchable': false, 'targets': [-1]}
        ]
     })});", array("inline"=>false));

    $count = 0;
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url(array('controller' => 'applications', 'action' => 'add_application')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Application</a>
  <a href="<?php echo $this->Html->url(array('controller' => 'applications', 'action' => 'index.csv')) ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm mr-2"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i> Download CSV</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Application Name</th>
        <th>Version</th>
        <th>Devices</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($applications as $post): ?>
        <tr>
<?php

	$start_class = 'btn-primary';
	if($post['Applications']['monitoring'] == 'true')
	{
		$start_class = 'btn-danger';
	}

  $count ++;

?>
        	<td id="application_<?php echo $count ?>"><?php echo $post['Applications']['name'] ?></td>
          <td><?php echo $post['Applications']['version'] ?></td>
          <td><?php echo $this->Html->link(count($post['Computer']), '/search/searchApplication/' . $post['Applications']['id']) ?></td>
        	<td width="20%" align="right">
            <?php if($post['Lifecycle']['id'] != NULL): ?>
            <a href="<?php echo $this->Html->url('/applications/lifecycle?q=' . $post['Applications']['name']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2" title="Has Lifecycle"><i class="mdi mdi-update icon-sm icon-inline text-white-50"></i></a>
            <?php endif; ?>
        	  <a id="icon_<?php echo $count ?>" href="#" title="Monitor Application" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 <?php echo $start_class ?>" onClick="return toggleMonitor(<?php echo $count ?>, <?php echo $post['Applications']['id'] ?>)"><i class="mdi mdi-eye icon-sm icon-inline text-white-50"></i></a>
            <a data-fancybox data-type="ajax" href="javascript:;" title="Assign Device" data-src="<?php echo $this->Html->url('/ajax/assign_application/' . $post['Applications']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i></a>
            <a href="<?php echo $this->Html->url(array('action' => 'delete_application', $post['Applications']['id'])) ?>" title="Delete Application" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-application" data-title="Confirm delete application"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
        	</td>
        </tr>

    <?php endforeach; ?>
      </tbody>
     </table>
   </div>
</div>
<script type="text/javascript">

	function toggleMonitor(id, app_id){

		if(!$('#icon_' + id).hasClass('btn-primary'))
		{
			//turn off
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_application_monitor/' + app_id + "/false");
		}
		else
		{
			//turn on
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_application_monitor/' + app_id + "/true");
		}

		$('#icon_' + id).toggleClass('btn-primary')
    $('#icon_' + id).toggleClass('btn-danger')

		return false;
	}
</script>
