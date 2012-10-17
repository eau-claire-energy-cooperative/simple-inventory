<?php echo $this->Html->link('Admin', array('action'=>"index")); ?> |
<?php echo $this->Html->link('Add Location', array('controller' => 'Admin', 'action' => 'addLocation')); ?> 
 <table>
    <?php foreach ($location as $post): ?>
    <tr>
    	
   
			
        <td> <?php echo  $post['Location']['location']; ?></td>
          <td>
         	<?php if($post['Location']['is_default'] == 'false'){
         			echo $this->Html->link('Set Default',array('action'=>'setDefaultLocation',$post['Location']['id']));
         		}
				else {
					echo "<b>Default</b>";	
				}
			?>
            <?php echo $this->Html->link('Edit', array('action' => 'editLocation', $post['Location']['id'])); ?>
            <?php echo $this->Form->postLink(
                'Delete',
                array('action' => 'deleteLocation', $post['Location']['id']),
                array('confirm' => 'Are you sure you want to delete record: ' . $post['Location']['location']. '?'));
				
            ?>
        </td>
 
    </tr>
    
    <?php endforeach; ?>
</table>