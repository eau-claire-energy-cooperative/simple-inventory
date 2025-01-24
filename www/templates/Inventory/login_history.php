<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Device: <?= $this->Html->link($computerName, ['controller'=>'inventory','action'=>'moreInfo',$id ]) ?></h6>
        </div>
        <div class="card-body">
          <table class="table table-striped">
          	<thead>
          		<th width="33%"><p align="left"><?= $this->Paginator->prev("<< Newer "); ?></p></th>
          		<th width="33%"><p align="center"><?= $this->Paginator->counter('Displaying page {{page}} of {{pages}}') ?></p></th>
          		<th align="right"><p align="right"><?= $this->Paginator->next("Older >> ") ?></p></th>
          	</thead>
          	<tbody>
          	<?php foreach($history as $aLogin): ?>
          	<tr>
          		<td><?= $aLogin['Username'] ?></td>
          		<td colspan="2"><?= $this->LegacyTime->niceShort($aLogin['LoginDate']) ?></td>
          	</tr>
          	<?php endforeach ?>
          	</tbody>
          </table>
        </div>
    </div>
  </div>
</div>
