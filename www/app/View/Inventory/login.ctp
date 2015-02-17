<?php echo $this->Form->create('User',array('url'=>'/inventory/login')) ?>
<table>
	<tr>
		<td><?php echo $this->Form->input('username') ?></td>
		<td><?php echo $this->Form->input('password') ?></td>
	</tr>
</table>
<?php echo $this->Form->end('Login') ?>

<script type="text/javascript">
$(document).ready(function(){
	$('#UserUsername').focus();
});
</script>
