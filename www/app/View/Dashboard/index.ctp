<script type="text/javascript">
    $(document).ready(function(){
		setInterval(refreshPage,2 * 60 * 1000);
	});
	
function refreshPage(){
	location.reload();
}
</script>

<div style="float:left;width:50%">
	<?php foreach($online as $computer): ?>
		<h3><?php echo $this->Html->image('/img/test-pass-icon.png') . ' ' . $this->Html->link($computer['Computer']['ComputerName'],'/inventory/moreInfo' . $computer['Computer']['id']) ?></h3>
	<?php endforeach ?>
</div>
<div style="float:right;width:50%">
	<?php foreach($offline as $computer): ?>
		<h3>
			<?php if($computer['Computer']['IsAlive'] == 'false'): ?>
				<?php echo $this->Html->image('/img/test-fail-icon.png') ?>
			<?php else: ?>
				<?php echo $this->Html->image('/img/test-pass-icon.png') ?>
			<?php endif; ?>
			<?php echo " " . $this->Html->link($computer['Computer']['ComputerName'],'/inventory/moreInfo' . $computer['Computer']['id']) ?>
		</h3>
		<div style="margin-left:50px;">
			<p>Last Updated: <?php echo $this->Time->niceShort($computer['Computer']['LastUpdated']); ?></p>
			
			<?php if(isset($computer['OfflineServices'])): ?>
				<?php foreach($computer['OfflineServices'] as $service): ?>
					<p><?php echo $this->Html->image('/img/test-fail-icon.png') .  " " . $service ?></p>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endforeach ?>
</div>
