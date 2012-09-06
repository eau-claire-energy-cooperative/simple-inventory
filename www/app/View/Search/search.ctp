<?php echo $this->Html->link('Home', '/inventory/home' ); ?>
<p></p>

<h3><?php echo $q ?></h3>
<p align="right"><?php echo $this->Html->link('Download CSV',$this->here . ".csv") ?></p>
<table>
    
   <?php foreach ($results as $post): ?>
    
    <tr>
    		
    	 <td width="20%"> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('controller'=>'inventory','action' => 'moreInfo', $post['Computer']['id'])); ?></td>
       	 <td width="20%"> <?php echo $post['Computer']['CurrentUser']; ?></td>
       	 <td width="20%"> <?php echo $locations[$post['Computer']['ComputerLocation']] ?></td>
       	 <td width="20%"> <?php echo $post['Computer']['Model']; ?></td>
       	 <td> <?php echo $post['Computer']['OS']; ?></td>
    </tr>
  
    <?php endforeach; ?>
	
</table>
 