
<?php echo $this->Html->link('Admin', array('action'=>"index")); ?>

<table>
	<tr>
		<th width="33%"><?echo $this->Paginator->prev("<< Newer "); ?></th>
		<th width="33%"><p align="center"><?echo $this->Paginator->counter('Displaying page {:page} of {:pages}') ?></p></th>
		<th align="right"><p align="right"><?php echo $this->Paginator->next("Older >> ") ?></p></th>
	</tr>
</table>
<div id="logDiv">
   <table>
    <?php foreach ($logs as $post): ?>
    <tr>
        <td> <?php echo  $post['Logs']['id']; ?></td>
        <td> <?php echo  $this->Time->nice( $post['Logs']['DATED']) ; ?></td>
        <td> <?php echo  $post['Logs']['LOGGER']; ?></td>
        <td> <?php echo  $post['Logs']['LEVEL']; ?></td>
        <td> <?php echo  $this->LogParser->parseMessage($inventory,$post['Logs']['MESSAGE']); ?></td>
    </tr>
    
    <?php endforeach; ?>
   </table>
</div> 
