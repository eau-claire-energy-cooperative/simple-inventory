<?php 
		echo $this->Html->script("jquery.tablesorter.js",false);
		echo $this->Html->script("table_utils.js",false);
?>

<?php echo $this->Html->link('Add Computer', array('controller' => 'Inventory', 'action' => 'add')); ?> | 

<?php echo $this->Html->link('Decommissioned Machines', array('action' => 'decommission')); ?> 
<p></p>

<table id="tableSort" class="tablesorter">
	<thead>
    <tr>
        <th>Computer Name	&uArr;&dArr;</th>
        <th>Current User	&uArr;&dArr;</th>
        <th>Operating System	&uArr;&dArr;</th>	
        <th>Memory	&uArr;&dArr;</th>
        <th>Location	&uArr;&dArr;</th>
     
    </tr>
	</thead>
	<tbody>
    

    <?php foreach ($computer as $post): ?>
    <tr>
        <td> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('action' => 'moreInfo', $post['Computer']['id'])); ?></td>
         <td><?php echo $post['Computer']['CurrentUser']; ?></td>
         <td><?php echo $post['Computer']['OS']; ?></td>
          <td><?php echo $post['Computer']['Memory']  ?> GB</td>
          
            <td><?php echo $this->Html->link( $post['Location']['location'], array('controller'=>'search','action' => 'search', 0,$post['Computer']['ComputerLocation'])); ?></td>
            
         <td>
         <?php echo $this->Html->link('Edit', array('action' => 'edit', $post['Computer']['id'])); ?> |
          <?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'delete', $post['Computer']['id']),
                array('confirm' => 'Are you sure?'));
            ?> |
            
             <?php echo $this->Html->link('Decommission', array('action' => 'confirmDecommission', $post['Computer']['id'])); ?>
        </td>
 
    </tr>
    
    <?php endforeach; ?>
</tbody>
</table>