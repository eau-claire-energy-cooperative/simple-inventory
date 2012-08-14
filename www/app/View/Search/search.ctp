<?php echo $this->Html->link('Home', '/inventory/home' ); ?>
<p></p>

<h3><?php echo $q ?></h3>
<table>
    
   <?php foreach ($results as $post): ?>
    
    <tr>
    		
    	 <td width="25%"> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('controller'=>'inventory','action' => 'moreInfo', $post['Computer']['id'])); ?></td>
       	 <td width="25%"> <?php echo $post['Computer']['CurrentUser']; ?></td>
       	 <td> <?php echo $locations[$post['Computer']['ComputerLocation']] ?></td>
    </tr>
  
    <?php endforeach; ?>
	
</table>
 