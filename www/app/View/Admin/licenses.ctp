<?php 
		echo $this->Html->script("fancybox/jquery.mousewheel-3.0.6.pack.js",false);
		echo $this->Html->script("fancybox/jquery.fancybox.js",false);
		echo $this->Html->css('jquery.fancybox.css');
?>

<script>
	$(document).ready(function() {	
    	$(".popup").fancybox({
		maxWidth	: 600,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
    });
      
</script>

<p><?php echo $this->Html->link('Add License', '/ajax/new_license', array('class'=>'popup fancybox.ajax')) ?></p>

<table>
	<?php foreach($licenses as $aLicense): ?>
	<tr>
		<td width="20%"><?php echo $this->Html->link($aLicense['Computer']['ComputerName'], '/inventory/moreInfo/' . $aLicense['License']['comp_id']) ?></td>
		<td width="25%"><?php echo $aLicense['License']['ProgramName'] ?></td>
		<td><?php echo $aLicense['License']['LicenseKey'] ?></td>
		<td width="12%"><?php echo $this->Html->link('Move', '/ajax/move_license/' . $aLicense['License']['id'] . '/' . $aLicense['License']['comp_id'], array('class'=>'popup fancybox.ajax')) ?> | 
			<?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'deleteLicense', $aLicense['License']['id']),
                array('confirm' => 'Are you sure you want to delete ' . $aLicense['License']['ProgramName']. '?'));
				
            ?></td>
	</tr>
	<?php endforeach ?>
</table>