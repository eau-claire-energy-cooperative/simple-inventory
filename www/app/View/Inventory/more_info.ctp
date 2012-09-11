<script type="text/javascript">
	function expandTable(id){
		
		$('#' + id + ' tr').each(function(index){
			if(index != 0)
			{
				$(this).toggle();
			}
		});
		
		return false;
	}
</script>

<?php echo $this->Html->link('Home', array('action' => 'home')); ?> |
<?php echo $this->Html->link('Edit', array('action' => 'edit', $computer['Computer']['id'])); ?>
<table>
    <tr>
        <th style="width: 200px;">Computer Name</th>
        <th style="width: 250px;">Location</th>
        <th style="width: 250px;">Current User</th>
        <th style="width: 250px;">Serial Number</th>
        <th style="width: 250px;">Asset ID</th>
     
    </tr>

    <tr>
        <td><?php echo $computer['Computer']['ComputerName']?></td>
        <td><?php echo $this->Html->link( $computer['Location']['location'], array('controller'=>'search','action' => 'search', 0, $computer['Computer']['ComputerLocation'])); ?></td>
         
          <td><?php echo $computer['Computer']['CurrentUser']?></td>
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
 		<th style="width: 250px;">Disk Space</th>
 		<th style="width: 250px;">Last Updated</th>
 		<th style="width: 250px;"></th>
     
    </tr>
    <tr>
		<td><?php echo $computer['Computer']['IPaddress']?></td>
		<td><?php echo $computer['Computer']['MACaddress']?></td>
		<td><?php echo $this->DiskSpace->toString($computer['Computer']['DiskSpace']) ?> (<?php echo $this->DiskSpace->compare($computer['Computer']['DiskSpace'],$computer['Computer']['DiskSpaceFree']) ?>% free)</td>
		<td><?php echo $this->Time->niceShort($computer['Computer']['LastUpdated']);?></td>
		<td></td> 
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
 
<table id="programs">
    <tr>
        <th><h1><a href="#" onClick="expandTable('programs')">Programs</a></h1></th>
    </tr>
    
    <?php foreach ($programs as $post): ?>
    <tr style="display:none">
    	<td> <?php echo $this->Html->link( $post['Programs']['program'] , '/search/searchProgram/' . $post['Programs']['program']); ?></td>
    </tr>
    
    <?php endforeach; ?>
 </table>
 
 <table id="services">
    <tr>
        <th colspan="3"><h1><a href="#" onClick="expandTable('services')">Services</a></h1></th>
    </tr>
    
    <?php foreach ($services as $post): ?>
    <tr style="display:none">
    	<td width="33%"> <?php echo $this->Html->link( $post['Service']['name'] , '/search/searchService/' . $post['Service']['name']); ?></td>
    	<td width="33%"><?php echo $post['Service']['startmode'] ?></td>
    	<td><?php echo $post['Service']['status'] ?></td>
    </tr>
    
    <?php endforeach; ?>
 </table>
 
