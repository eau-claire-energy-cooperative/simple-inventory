<div class="row">
  <div class="col-xl-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <p>This page is offered as a convienence to see the current state of the email queue. The queue will be purged as messages are sent. </p>
        <table class="table table-striped">
          <tr>
            <thead>
                <th>ID</th>
                <th>Recipient</th>
                <th>Subject</th>
                <th></th>
            </thead>
          </tr>
          <?php foreach($email_queue as $email): ?>
        	<tr>
            <td><?= $email['id'] ?></td>
            <td><?= $email['recipient'] ?></td>
            <td><?= $email['subject'] ?></td>
        		<td>
              <a data-title="Delete Message" href="<?= $this->Url->build("/admin/email_queue/". $email['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-setting"><i class="material-icons mi-sm mi-inline text-white-50"></i> Delete</a>
        		</td>
        	</tr>
        	<?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
