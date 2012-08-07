
<?php echo $this->Html->link('Home', array('action' => 'home')); ?> 
				
<?php 

echo $this->Form->create('Computer');

//create two variables for populating options
$options=array('Yes' => 'Yes', 'No' => 'No'); 
$attributes=array('legend' => false,'separator'=>'<br>'); 

?> 	
<table>
	<tr>
   		<td class="radio"><p>Did you wipe the hard drive?</p><?php echo $this->Form->radio('WipedHD', $options, $attributes);?></td>
   		<td><?php echo $this->Form->input('RedeployedAs') ?></td>   			
	</tr>
	<tr>
	   	<td class="radio"><p>Was the machine recycled?</p><?php echo $this->Form->radio('Recycled', $options, $attributes);?></td>
		<td><?php echo $this->Form->input('notes', array('rows' => '3','value'=>$this->data['Computer']['notes'])) ?></td>
	</tr>	

	</table>
<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
<?php echo $this->Form->end('Update');?>