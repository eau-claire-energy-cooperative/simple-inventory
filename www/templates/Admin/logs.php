
<div class="row">
  <div class="col-md-4"><?= $this->Paginator->prev("<< Newer "); ?></div>
	<div class="col-md-4"><p align="center"><?= $this->Paginator->counter('Displaying page {{page}} of {{pages}}') ?></p></div>
	<div class="col-md-4"><p align="right"><?= $this->Paginator->next("Older >> ") ?></p></div>
</div>
<table class="table table-striped">
  <tbody>
    <?php foreach ($logs as $post): ?>
    <tr>
        <td> <?=  $post['id']; ?></td>
        <td> <?=  $this->Time->nice( $post['DATED']) ; ?></td>
        <td> <?=  $post['LOGGER']; ?></td>
        <td> <?=  $post['LEVEL']; ?></td>
        <td> <?=  $this->LogParser->parseMessage($inventory,$post['MESSAGE']); ?></td>
    </tr>

    <?php endforeach; ?>
  </tbody>
</table>
