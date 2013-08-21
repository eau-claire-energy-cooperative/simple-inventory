
<p><?php echo $this->Html->link('Compare AD to Inventory', '/inventory/active_directory_sync/compare'); ?> |
<?php echo $this->Html->link('Find Old Computers', '/inventory/active_directory_sync/find_old'); ?></p>  

<p>Use these tools to find computers that may need to be added or decomissioned</p>

<?php
if(isset($computers)) :
?>
<table width="50%">
<?php 
	$keys = array_keys($computers);
	
	foreach($keys as $aComputer)
	{
		
		echo "<tr>";
		echo "<td>" . $aComputer . "</td>";
		echo "<td>" . $computers[$aComputer]['value'] . "</td>";
		echo "</tr>";
	}
?>
</table>
<?php endif; ?>