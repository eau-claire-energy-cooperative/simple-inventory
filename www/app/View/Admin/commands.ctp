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

<table width="100%">
	<tr>
		<td width="60%"><h2>Currently Scheduled Tasks</h2></td>
		<td><h2>Available Commands</h2></td>
	</tr>
	<tr>
		<td>
			<?php foreach($all_schedules as $schedule): ?>
			<h3><?php echo $schedule['Command']['name']; ?></h3> 
				
				<?php eval("\$schedule_params = " . $schedule['Schedule']['parameters'] . ";"); ?>
				
				<ul>	
					<li>Schedule: <?php echo $schedule['Schedule']['schedule'] ?></li>	
				<?php foreach(array_keys($schedule_params) as $aKey): ?>
					<li><?php echo $aKey . ": " . $schedule_params[$aKey] ?></li>
				<?php endforeach; ?>	
				</ul>
				<p align="right" style="margin-right:10px"><?php echo $this->Html->link('Delete','/admin/schedule/' . $schedule['Schedule']['id']); ?></p>
			<?php endforeach; ?>
		</td>
		<td>
			<table>

			<?php foreach($all_commands as $command): ?>
				<tr>
					<td><h3><?php echo $command['Command']['name'] ?></h3></td>
					<td><?php echo $this->Form->button('Add',array('class'=>'popup fancybox.ajax','href'=>'/inventory/ajax/setup_command/' . $command['Command']['id'])); ?></td> 
				</tr>		
			<?php endforeach ?>
				
			</table>
		</td>
	</tr>
</table>

<h1>Cron syntax </h1>
<p>A <a href="http://en.wikipedia.org/wiki/Cron ">Cron expression</a> is made up for 5 parts (see below).

<p>.--------------- minute (0 - 59)<br>
|   .------------ hour (0 - 23)<br>
    |   |   .--------- day of month (1 - 31)<br>
    |   |   |   .------ month (1 - 12) or Jan, Feb ... Dec<br>
    |   |   |   |  .---- day of week (0 - 6) or Sun(0 or 7), Mon(1) ... Sat(6)<br>
    V   V   V   V  V<br>
    *   *   *   *  *</p>
<p>Example:</p>
<ul>
	<li>0 */5 ** 1-5 - runs every five hours Monday - Friday</li>
	<li>0,15,30,45 0,15-18 * * * - runs every quarter hour during midnight hour and 3pm-6pm</li>
</ul>
