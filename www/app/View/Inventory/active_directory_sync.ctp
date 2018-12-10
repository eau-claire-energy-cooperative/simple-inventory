
<p><?php echo $this->Html->link('Compare AD to Inventory', '/inventory/active_directory_sync/compare'); ?> |
<?php echo $this->Html->link('Find Old Computers', '/inventory/active_directory_sync/find_old?days_old=60'); ?></p>  

<p>Use these tools to find computers that may need to be added or decomissioned.</p>
<p>Searching AD Tree: <b><?php echo $baseDN ?></b></p>
<?php if(isset($computers)) : ?>
<table width="50%">

<?php if($currentAction == 'find_old'): ?>
	<?php echo $this->Form->input('days_old',array('type' => 'select','onchange'=>'updateDays()','options' => array('30'=>'30 Days','60'=>'60 days','90'=>'90 days','120'=>'120 days'),'selected'=>$days_old,'label'=>false,'style'=>'float:right')); ?>
<?php endif; ?>
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

<script type="text/javascript">
function updateDays(){
	window.location.href = '<?php echo $this->webroot ?>inventory/active_directory_sync/find_old?days_old=' + $('#days_old').val();
}
</script>
