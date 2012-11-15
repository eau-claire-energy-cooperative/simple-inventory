<?php echo $this->Html->link('Admin', array('action'=>"index")); ?>

<table id="programs">
    <?php foreach ($all_programs as $post): ?>
    <tr>
    	<td width="75%"><?php echo $this->Html->link( $post['Programs']['program'] , '/search/searchProgram/' . $post['Programs']['program']); ?></td>
    	<td><p><b>
    		<?php 
    			if(key_exists($post['Programs']['program'],$restricted_programs))
				{
					echo $this->Html->link('Restricted','/admin/toggle_restricted/true/' . $restricted_programs[$post['Programs']['program']],array('class'=>'red'));
				}
				else
				{
					echo $this->Html->link('Mark Restricted','/admin/toggle_restricted/false/' . $post['Programs']['program']);
				}
    		?>
    	</b></p>
    	</td>
    </tr>
    
    <?php endforeach; ?>
 </table>