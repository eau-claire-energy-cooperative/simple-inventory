<p align="right">Computer: <?php echo $this->Html->link($computerName,array('controller'=>'inventory','action'=>'moreInfo',$id)) ?></p>
<table>
	<tr>
		 <th>Username</th>
		 <th>Login Date</th>
	</tr>
	<?php foreach($history as $aLogin): ?>
	<tr>
		<td><?php echo $aLogin['Username'] ?></td>
		<td><?php echo $this->Time->niceShort($aLogin['LoginDate']) ?></td>
	</tr>
	<?php endforeach ?>
</table>