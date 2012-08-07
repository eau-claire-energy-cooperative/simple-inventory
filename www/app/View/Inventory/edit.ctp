<?php echo $this->Html->link('Home', array('action' => 'home')); ?>
<?php echo $this->Form->create('Computer', array('url' => '/Inventory/edit')); ?>
 
		<table>
		<tr>
   			<td style="width: 350px;"><?php echo $this->Form->input('ComputerName');?></td>
   			<td><?php echo $this->Form->input('ComputerLocation',array('type' => 'select', 'id' => 'location_id', 'options' => $location)); ?></td>
   			<td><?php echo $this->Form->input('CurrentUser');?></td>
		</tr>
		<tr>
			<td><?php echo $this->Form->input('SerialNumber');?></td>
			<td><?php echo $this->Form->input('AssetId');?></td>
			
    	</tr>
   
    	<tr>
    		<td><?php echo $this->Form->input('Model');?></td>
    		<td><?php echo $this->Form->input('OS');?></td>
    	</tr>
    	 <tr>
    		 <td><?php echo $this->Form->input('CPU');?></td>
    		<td><?php echo $this->Form->input('Memory');?></td>
    		<td><?php echo $this->Form->input('NumberOfMonitors');?></td>
    	</tr>
    	
    	<tr>
    		<td><?php echo $this->Form->input('IPaddress',array('label'=>"IP Address"));?></td>
    		<td><?php echo $this->Form->input('MACaddress',array('label'=>'MAC Address'));?></td>
    		<td><?php echo $this->Form->input('DiskSpace');?></td>
    	</tr>
    	<tr>
    		<td colspan="2"><?php echo $this->Form->input('notes'); ?></td>
    	</tr>
	</table>
		<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
		<?php echo $this->Form->end('Update');?>
