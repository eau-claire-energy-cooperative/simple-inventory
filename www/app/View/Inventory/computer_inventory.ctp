<?php 
		echo $this->Html->script("jquery.tablesorter.js",false);
		echo $this->Html->script("table_utils.js",false);
?>

<?php echo $this->Html->link('Add Computer', array('controller' => 'Inventory', 'action' => 'add')); ?> 
<?php if($settings['ldap_computers_basedn'] != ''): ?> | 
<?php echo $this->Html->link('Active Directory Sync', array('controller' => 'Inventory', 'action' => 'active_directory_sync')); ?>
<?php endif; ?>
<p></p>

<table id="tableSort" class="tablesorter">
	<thead>
    <tr>
        <th>Computer Name	&uArr;&dArr;</th>
        <th>Current User	&uArr;&dArr;</th>
        <th>Operating System	&uArr;&dArr;</th>	
        <th>Memory	&uArr;&dArr;</th>
        <th>Model &uArr;&dArr;</th>
        <th>Tag	&uArr;&dArr;</th>
        <th>Last Update	&uArr;&dArr;</th>
    </tr>
	</thead>
	<tbody>
    

    <?php foreach ($computer as $post): ?>
    <tr>
        <td> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('action' => 'moreInfo', $post['Computer']['id'])); ?></td>
         <td><?php echo $post['Computer']['CurrentUser']; ?></td>
         <td><?php echo $post['Computer']['OS']; ?></td>
         <td><?php echo $post['Computer']['Memory']  ?> GB</td>
         <td><?php echo $post['Computer']['Model'] ?></td>
         <td><?php echo $this->Html->link( $post['Location']['location'], array('controller'=>'search','action' => 'search', 0,$post['Computer']['ComputerLocation'])); ?></td>
         <td><?php echo $this->Time->format('m/d/Y',$post['Computer']['LastUpdated']) ?></td>   
    </tr>
    
    <?php endforeach; ?>
</tbody>
</table>