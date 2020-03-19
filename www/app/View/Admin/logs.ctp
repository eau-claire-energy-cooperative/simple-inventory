<div class="row">
  <div class="col-md-4"><?php echo $this->Paginator->prev("<< Newer "); ?></div>
	<div class="col-md-4"><p align="center"><?php echo $this->Paginator->counter('Displaying page {:page} of {:pages}') ?></p></div>
	<div class="col-md-4"><p align="right"><?php echo $this->Paginator->next("Older >> ") ?></p></div>
</div>
<table class="table table-striped">
  <tbody>
    <?php foreach ($logs as $post): ?>
    <tr>
        <td> <?php echo  $post['Logs']['id']; ?></td>
        <td> <?php echo  $this->Time->nice( $post['Logs']['DATED']) ; ?></td>
        <td> <?php echo  $post['Logs']['LOGGER']; ?></td>
        <td> <?php echo  $post['Logs']['LEVEL']; ?></td>
        <td> <?php echo  $this->LogParser->parseMessage($inventory,$post['Logs']['MESSAGE']); ?></td>
    </tr>
    
    <?php endforeach; ?>
  </tbody>
</table>

