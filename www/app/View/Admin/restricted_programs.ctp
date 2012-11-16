<?php $count = 0; ?>
<table id="programs">
    <?php foreach ($all_programs as $post): ?>
    <tr>
<?php 
	$row_class = '';
	$count ++;
	
	if(key_exists($post['Programs']['program'],$restricted_programs))
	{
		$row_class = "restricted";
		//echo $this->Html->link('Restricted','/admin/toggle_restricted/true/' . $restricted_programs[$post['Programs']['program']],array('class'=>'red'));
	}
	else
	{
		//echo $this->Html->link('Mark Restricted','/admin/toggle_restricted/false/' . $post['Programs']['program']);
	}

?>
    	<td class="<?php echo $row_class ?>" id="program_<?php echo $count ?>"><span class="name"><?php echo $this->Html->link( $post['Programs']['program'] , '/search/searchProgram/' . $post['Programs']['program']); ?></span>
    		<span style="float:right"><?php echo $this->Html->link('Toggle','#',array("onClick"=>"return toggleProgram(" . $count . ")")) ?></span>
    	</td>
    </tr>
    
    <?php endforeach; ?>
 </table>
 
<script type="text/javascript">
	
	function toggleProgram(id){
		program_name = $('#program_'+ id + " span:first-child a").html();
		
		if($('#program_' + id).hasClass('restricted'))
		{
			//turn off
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_restricted/true/' + program_name);
			$('#program_' + id).removeClass('restricted');
		}
		else
		{
			//turn on
			$.ajax('<?php echo $this->webroot ?>ajax/toggle_restricted/false/' + program_name);
			$('#program_' + id).addClass('restricted');
		}
		
		return false;
	}
</script>
