<p align="right">Computer: <?php echo $this->Html->link($computerName,array('controller'=>'inventory','action'=>'moreInfo',$id)) ?></p>

<table>
	<tr>
		<th width="33%"><?echo $this->Paginator->prev("<< Newer "); ?></th>
		<th width="33%"><p align="center"><?echo $this->Paginator->counter('Displaying page {:page} of {:pages}') ?></p></th>
		<th align="right"><p align="right"><?php echo $this->Paginator->next("Older >> ") ?></p></th>
	</tr>
	<?php foreach($history as $aLogin): ?>
	<tr>
		<td><?php echo $aLogin['ComputerLogin']['Username'] ?></td>
		<td colspan="2"><?php echo $this->Time->niceShort($aLogin['ComputerLogin']['LoginDate']) ?></td>
	</tr>
	<?php endforeach ?>
</table>