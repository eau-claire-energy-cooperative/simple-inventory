<?php 
		echo $this->Html->script("fancybox/jquery.mousewheel-3.0.6.pack.js",false);
		echo $this->Html->script("fancybox/jquery.fancybox.js",false);
		echo $this->Html->css('jquery.fancybox.css');
?>

<script type="text/javascript">
    $(document).ready(function(){
    	checkRunning();
		setInterval(checkRunning,40 * 1000);
		
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

	function checkRunning(){
		$.getJSON('<?php echo $this->webroot ?>ajax/checkRunning/<?php echo $computer['Computer']['ComputerName'] ?>',function(data){
			if(data.received == data.transmitted)
			{
				if(<?php echo $settings['show_computer_commands']?>)
				{
					$('#is_running').html('<a href="#" onClick="shutdown(\'<?php echo $computer['Computer']['ComputerName'] ?>\',false)">Shutdown</a> | <a href="#" onClick="shutdown(\'<?php echo $computer['Computer']['ComputerName'] ?>\',true)">Restart</a>');
				}
				else
				{
					$('#is_running').html('Running');
				}
				$('#is_running').removeClass('red');
			}
			else
			{
				if(<?php echo $settings['show_computer_commands']?>)
				{
					$('#is_running').html('<a href="#" onClick="wol(\'<?php echo $computer['Computer']['MACaddress'] ?>\')">Turn On</a>');
					$('#is_running').removeClass('red');
				}
				else
				{
					$('#is_running').html('Not Running');
					$('#is_running').addClass('red');
				}
			}
		});
	}

	function expandTable(id){
		
		$('#' + id + ' tr').each(function(index){
			if(index != 0)
			{
				$(this).toggle();
			}
		});
		
		return false;
	}
	
	function shutdown(host,shouldRestart){
		
		if(confirm('Shutdown or Restart this computer?'))
		{
			$.ajax('<?php echo $this->webroot ?>ajax/shutdown/' + host + '/' + shouldRestart);
		}
		return false;
	}
	
	function wol(mac){
		$.ajax('<?php echo $this->webroot ?>ajax/wol?mac=' + mac);
	}
	
</script>

<?php echo $this->Html->link('Edit', array('action' => 'edit', $computer['Computer']['id'])); ?> | 
<?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'delete', $computer['Computer']['id']),
                array('confirm' => 'Are you sure?'));
            ?>
<span style="float:right"><?php echo $this->Html->link('Decommission', array('action' => 'confirmDecommission', $computer['Computer']['id'])); ?>
<?php if(file_exists(WWW_ROOT . '/drivers/' . str_replace(' ','_',$computer['Computer']['Model']) . '.zip')): ?>
| <?php echo $this->Html->link("Download Drivers","/drivers/" . str_replace(' ','_',$computer['Computer']['Model']) . ".zip") ?>
<?php else: ?>
| <?php echo $this->Html->link("Upload Drivers",'/ajax/uploadDrivers/' . $computer['Computer']['id'],array('class'=>'popup fancybox.ajax')) ?>
<?php endif; ?>
 
</span>
<table>
    <tr>
        <th style="width: 200px;">Computer Name</th>
        <th style="width: 250px;">Tag</th>
        <th style="width: 250px;">Current User</th>
        <th style="width: 250px;">Serial Number</th>
        <th style="width: 250px;">Asset ID</th>
     
    </tr>

    <tr>
        <td><?php echo $computer['Computer']['ComputerName']?></td>
        <td><?php echo $this->Html->link( $computer['Location']['location'], array('controller'=>'search','action' => 'search', 0, $computer['Computer']['ComputerLocation'])); ?></td>
         
          <td><?php echo $this->Html->link($computer['Computer']['CurrentUser'],array('controller'=>'inventory','action'=>'loginHistory',$computer['Computer']['id'])); ?></td>
           <td><?php echo $computer['Computer']['SerialNumber']?></td>
            <td><?php echo $computer['Computer']['AssetId']?> </td>

    </tr>

</table>

<table>
    <tr>
        <th style="width: 200px;">Model</th>
        <th style="width: 250px;">Operating System</th>
        <th style="width: 250px;">CPU</th>
        <th style="width: 250px;">Memory</th>
        <th style="width: 250px;">Number of Monitors</th>
     
    </tr>
	    <tr>
        <td> <?php echo $this->Html->link($computer['Computer']['Model'], array('controller'=>'search','action' => 'search', 1, $computer['Computer']['Model'])); ?></td>
       
        <td><?php echo $this->Html->link( $computer['Computer']['OS'], array('controller'=>'search','action' => 'search', 2, $computer['Computer']['OS'])); ?></td> <!--  $comparisonID,$columnID,$modelID,$nameID -->
      
          <td><?php echo $computer['Computer']['CPU']?></td>
    
           <td> <?php echo $this->Html->link($computer['Computer']['Memory'] . " GB", array('controller'=>'search','action' => 'search', 3, $computer['Computer']['Memory'])); ?> 
           	    (<?php echo $this->DiskSpace->compare($computer['Computer']['Memory'],$computer['Computer']['MemoryFree']) ?>% free)</td>
        

             <td> <?php echo $this->Html->link( $computer['Computer']['NumberOfMonitors'], array('controller'=>'search','action' => 'search', 4, $computer['Computer']['NumberOfMonitors'])); ?></td>
         </tr>
        
	
</table>

<table>
		
    <tr>
        <th style="width: 200px;">IP Address</th>
        <th style="width: 250px;">MAC Address</th>
 		<th style="width: 250px;">Drive Space</th>
 		<th style="width: 250px;">Last Updated</th>
 		<th style="width: 250px;"></th>
     
    </tr>
    <tr>
		<td><?php echo $computer['Computer']['IPaddress']?></td>
		<td><?php echo $computer['Computer']['MACaddress']?></td>
		<td>
			<?php foreach($computer['Disk'] as $aDisk): ?>
				<?php echo $aDisk['label'] . " - " . $this->DiskSpace->toString($aDisk['total_space']) ?> (<?php echo $this->DiskSpace->compare($aDisk['total_space'],$aDisk['space_free']) ?>% free)<br>
			<?php endforeach; ?>
		</td>
		<td><?php echo $this->Time->niceShort($computer['Computer']['LastUpdated']);?></td>
		<td><p id="is_running" class="red">Not Running</p></td> 
     </tr>
       
</table> 

<?php if($computer['Computer']['notes'] != ''): ?>
<table>
	<tr>
		<th>Notes</th>
	</tr>
	<tr>
		<td><?php echo $computer['Computer']['notes']?></td>
	</tr>
</table> 
 <?php endif; ?>
 
<?php if(count($programs) > 0): ?>
<table id="programs">
    <tr>
        <th><h1><a href="#" onClick="expandTable('programs')">Programs</a></h1></th>
    </tr>
    
    <?php foreach ($programs as $post): ?>
    <tr style="display:none">
<?php 
	$row_class = '';
	
	if(key_exists($post['Programs']['program'],$restricted_programs))
	{
		$row_class = 'restricted';
	}
?>
    	<td class="<?php echo $row_class ?>"> <?php echo $this->Html->link( $post['Programs']['program'] . " v" . $post["Programs"]["version"], '/search/searchProgram/' . $post['Programs']['program']); ?></td>
    </tr>
    
    <?php endforeach; ?>
 </table>
 <?php endif; ?>

 <?php if(count($services) > 0): ?>
 <table id="services">
    <tr>
        <th colspan="4"><h1><a href="#" onClick="expandTable('services')">Services</a></h1></th>
    </tr>
    
    <?php foreach ($services as $post): ?>
    <tr style="display:none">
    	<td width="33%"> <?php echo $this->Html->link( $post['Service']['name'] , '/search/searchService/' . $post['Service']['name']); ?></td>
    	<td width="33%"><?php echo $post['Service']['startmode'] ?></td>
    	<td><?php echo $post['Service']['status'] ?></td>
    </tr>
    
    <?php endforeach; ?>
 </table>
 <?php endif ?>
 
