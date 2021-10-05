
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Device: <?php echo $this->Html->link($computerName,array('controller'=>'inventory','action'=>'moreInfo',$id)) ?></h6>
        </div>
        <div class="card-body">
          <table class="table table-striped">
          	<thead>
          		<th width="33%"><?php echo $this->Paginator->prev("<< Newer "); ?></th>
          		<th width="33%"><p align="center"><?php echo $this->Paginator->counter('Displaying page {:page} of {:pages}') ?></p></th>
          		<th align="right"><p align="right"><?php echo $this->Paginator->next("Older >> ") ?></p></th>
          	</thead>
          	<tbody>
          	<?php foreach($history as $aLogin): ?>
          	<tr>
          		<td><?php echo $aLogin['ComputerLogin']['Username'] ?></td>
          		<td colspan="2"><?php echo $this->Time->niceShort($aLogin['ComputerLogin']['LoginDate']) ?></td>
          	</tr>
          	<?php endforeach ?>
          	</tbody>
          </table>
        </div>
    </div>
  </div>
</div>
