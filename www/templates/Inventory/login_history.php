<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Device: <?= $this->Html->link($computerName, ['controller'=>'inventory','action'=>'moreInfo',$id ]) ?></h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6"><?= $this->Paginator->counter('Displaying page {{page}} of {{pages}}') ?></div>
            <div class="col-md-6">
              <div style="float:right">
                <ul class="pagination">
                  <?= $this->Paginator->prev("Previous"); ?>
                  <?= $this->Paginator->numbers(['before'=>'', 'modulus'=>2]) ?>
                  <?= $this->Paginator->next("Next") ?>
                </ul>
              </div>
            </div>
          </div>
          <table class="table table-striped">
          	<thead>
          		<th><p>User</p></th>
          		<th><p>Login Timestamp</p></th>
          	</thead>
          	<tbody>
          	<?php foreach($history as $aLogin): ?>
          	<tr>
          		<td><?= $aLogin['Username'] ?></td>
          		<td><?= $this->LegacyTime->niceShort($aLogin['LoginDate']) ?></td>
          	</tr>
          	<?php endforeach ?>
          	</tbody>
          </table>
        </div>
    </div>
  </div>
</div>
