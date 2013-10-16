<script type="text/javascript">
    $(document).ready(function(){
		setInterval(refreshPage,20 * 1000);
	});
	
function refreshPage(){
	location.reload();
}
</script>

<div style="float:left;width:50%">
	<?php foreach($online as $computer): ?>
		<h2><?php echo $this->Html->image('/img/test-pass-icon.png') . ' ' . $this->Html->link($computer['Computer']['ComputerName'],'/inventory/moreInfo/' . $computer['Computer']['id']) ?></h2>
	<?php endforeach ?>
</div>
<div style="float:right;width:50%">
	<?php foreach($offline as $computer): ?>
		<h2>
			<?php if($computer['Computer']['IsAlive'] == 'false'): ?>
				<?php echo $this->Html->image('/img/test-fail-icon.png') ?>
			<?php else: ?>
				<?php echo $this->Html->image('/img/test-pass-icon.png') ?>
			<?php endif; ?>
			<?php echo " " . $this->Html->link($computer['Computer']['ComputerName'],'/inventory/moreInfo/' . $computer['Computer']['id']) ?>
		</h2>
		<div style="margin-left:50px;">
			<h3>Last Updated: <?php echo $this->Time->niceShort($computer['Computer']['LastUpdated']); ?></h3>
			
			<?php if(isset($computer['OfflineServices'])): ?>
				<?php foreach($computer['OfflineServices'] as $service): ?>
					<h3><?php echo $this->Html->image('/img/test-fail-icon.png') .  " " . $service ?></h3>
				<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if(count($computer['DiskAlert']) > 0): ?>
				<?php foreach($computer['DiskAlert'] as $aDisk): ?>
					<h3><?php echo $this->Html->image('/img/test-fail-icon.png') . ' ' . $aDisk['label'] . ' - ' . $this->DiskSpace->compare($aDisk['total_space'],$aDisk['space_free']) ?>% free</h3>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endforeach ?>
</div>
