<?php 
		echo $this->Html->script("jquery.tablesorter.js",false);
		echo $this->Html->script("table_utils.js",false);
?>
	
<?php echo $this->Html->link('Home', array('action' => 'home')); ?> 
<p></p>
		  
		<table id="tableSort" class="tablesorter">
	<thead>

        <th>Computer Name	&uArr;&dArr;</th>
        <th>Redeployed As	&uArr;&dArr;</th>
        <th>Wiped Hard Drive	&uArr;&dArr;</th>
        <th></th>	
        <th>Recycled	&uArr;&dArr;</th>
        <th></th>
        <th>Old Location	&uArr;&dArr;</th>
     
  
	</thead>
	<tbody>
    <!-- Here is where we loop through our $posts array, printing out post info -->

    <?php foreach ($decommission as $post): ?>
    <tr>
        
         <td> <?php echo $this->Html->link( $post['Decommissioned']['ComputerName'] , array('action' => 'moreInfoDecommissioned', $post['Decommissioned']['id'])); ?></td>
           <td><?php echo $post['Decommissioned']['RedeployedAs']; ?></td>
             <td><?php echo $post['Decommissioned']['WipedHD']; ?> 
             	<div style="float:right; "><?php echo $this->Html->link('Yes', array( 'action' => 'changeWipeStatus',$post['Decommissioned']['id'], 'Yes'));  ?> |
             							  <?php echo $this->Html->link('No', array('action' => 'changeWipeStatus',$post['Decommissioned']['id'],'No')); ?>
             	</div></td>
             	<td></td>
               <td><?php echo $post['Decommissioned']['Recycled']; ?>
               <div style="float:right; "><?php echo $this->Html->link('Yes', array( 'action' => 'changeRecycledStatus',$post['Decommissioned']['id'], 'Yes'));  ?> |
             							  <?php echo $this->Html->link('No', array('action' => 'changeRecycledStatus',$post['Decommissioned']['id'],'No')); ?>
             	</div></td>
               <td></td>
                 <td><?php echo $post['Location']['location']; ?></td>
                 
                   
 
    </tr>
         <?php endforeach; ?>  

</tbody>
</table>
