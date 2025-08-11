<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8"><?= $this->Paginator->counter('Displaying page {{page}} of {{pages}}') ?></div>
      	  <div class="col-md-4">
            <?= $this->Form->create(null, ['url'=>'/admin/logs']) ?>
            <div class="form-group row">
              <label for="fQuery" class="col-sm-4 col-form-label text-right">Filter: </label>
              <div class="col-sm-8">
                <?= $this->Form->text('q', ['id'=>'fQuery', 'class'=>'form-control']) ?>
              </div>
            </div>
            <?= $this->Form->end() ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-8">
            <div style="float:right">
              <ul class="pagination">
                <?= $this->Paginator->prev("Previous"); ?>
                <?= $this->Paginator->numbers(['before'=>'', 'modulus'=>3]) ?>
                <?= $this->Paginator->next("Next") ?>
              </ul>
            </div>
          </div>
        </div>
        <table class="table table-striped">
          <thead>
            <th>ID</th>
            <th>Date</th>
            <th>Area</th>
            <th>Level</th>
            <th>User</th>
            <th>Message</th>
          </thead>
          <tbody>
            <?php foreach ($logs as $post): ?>
            <tr>
                <td><?= $post['id'] ?></td>
                <td><?= $this->Time->nice( $post['DATED']) ?></td>
                <td><?= $post['LOGGER'] ?></td>
                <td><?= $post['LEVEL'] ?></td>
                <td><?= $post['USER'] ?></td>
                <td><?= $this->LogParser->parseMessage($inventory,$post['MESSAGE']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
