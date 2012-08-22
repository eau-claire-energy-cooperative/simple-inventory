<?php echo $this->Html->link('Admin', array('action'=>"index")); ?> |
<?php echo $this->Html->link('Add Location', array('controller' => 'Admin', 'action' => 'addLocation')); ?> 
 <table>
    <?php foreach ($location as $post): ?>
    <tr>
    	
   
			
        <td> <?php echo  $post['Location']['location']; ?></td>
          <td>
         	 <?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'deleteLocation', $post['Location']['id']),
                array('confirm' => 'Are you sure you want to delete record: ' . $post['Location']['location']. '?'));
				
            ?>
            <?php echo $this->Html->link('Edit', array('action' => 'editLocation', $post['Location']['id'])); ?>
        </td>
 
    </tr>
    
    <?php endforeach; ?>
</table>