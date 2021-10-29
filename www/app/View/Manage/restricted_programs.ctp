<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', false);

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
      $('#dataTable').DataTable({
        paging: true,
        pageLength: 100,
        dom: '<\"top\"ifp>rt',
        columnDefs: [
          {'searchable': false, 'targets': [-1]}
        ]
     })});", array("inline"=>false));

    $count = 0;
?>
<table id="dataTable" class="table table-striped">
  <thead>
    <th>Program Name</th>
    <th></th>
  </thead>
  <tbody>
    <?php foreach ($all_programs as $post): ?>
    <tr>
<?php

	$start_class = 'btn-primary';
  $start_text = 'Ban';
	if(key_exists($post['Programs']['program'],$restricted_programs))
	{
		$start_class = 'btn-danger';
    $start_text = 'Allow';
	}

  $count ++;

?>
    	<td id="program_<?php echo $count ?>"><?php echo $this->Html->link( $post['Programs']['program'] , '/search/searchProgram/' . $post['Programs']['program']); ?></td>
    	<td width="20%" align="right">
    	  <a id="icon_<?php echo $count ?>" href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 <?php echo $start_class ?>" onClick="return toggleProgram(<?php echo $count ?>)"><i class="material-icons mi-sm mi-inline text-white-50">block</i> <span><?php echo $start_text ?></span></a>
    	</td>
    </tr>

    <?php endforeach; ?>
  </tbody>
 </table>

<script type="text/javascript">

	function toggleProgram(id){
		program_name = $('#program_'+ id + " a").html();

		if(!$('#icon_' + id).hasClass('btn-primary'))
		{
			//turn off
      $('#icon_' + id + " span").text('Ban');
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_restricted/true/' + program_name);
		}
		else
		{
			//turn on
			$('#icon_' + id + " span").text('Allow');
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_restricted/false/' + program_name);
		}

		$('#icon_' + id).toggleClass('btn-primary')
    $('#icon_' + id).toggleClass('btn-danger')

		return false;
	}
</script>
