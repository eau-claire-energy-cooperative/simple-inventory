<?php $statusSort = array("new"=>0, "active"=>1, "approved"=>2, "denied"=>3); ?>
<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
    $this->Html->scriptBlock("$(document).ready(function() {
      $('a.delete-request').confirm({
        title: 'Delete Request',
        content: 'Are you sure you want to delete this request?',
        buttons: {
            yes: function(){
                location.href = this.\$target.attr('href');
            },
            cancel: function(){

            }
        }
      });
   });", ["block"=>true])
?>
<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/checkout/index') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> New Checkout Request</a>
</div>

<div class="row">
  <?php if(count($new) > 0): ?>
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">New Requests</h6>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <th>Status</th>
            <th>Employee Name</th>
            <th>Check Out Date</th>
            <th>Check In Date</th>
            <th>Device</th>
            <th></th>
          </thead>
          <tbody>
            <?php foreach ($new as $post): ?>
            <tr>
              <td data-sort="<?= $statusSort[$post['status']] ?>">
                <?= ucwords($post['status']) ?>
              </td>
              <td><?= $post['employee_name']?></td>
              <td><?= $post['check_out_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td><?= $post['check_in_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td>
                <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['device']['name'] ?>
              </td>
              <td align="right">
                <?php if(count($post['computer']) == 0): ?>
                <a href="<?= $this->Url->build('/checkout/approve/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
                <?php endif ?>
                <?php if(in_array($post['status'], array('new', 'approved'))): ?>
                <a href="<?= $this->Url->build('/checkout/deny/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if(count($active) > 0): ?>
  <div class="col-xl-12">
    <div class="card border-left-success shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Checked Out Devices</h6>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <th>Status</th>
            <th>Employee Name</th>
            <th>Check Out Date</th>
            <th>Check In Date</th>
            <th>Device</th>
            <th></th>
          </thead>
          <tbody>
            <?php foreach ($active as $post): ?>
            <?php
              // determine if the check in date has passed
              $highlight = "";
              if($post['check_in_date']->addDays(1)->lessThan($now))
              {
                $highlight = "table-danger";
              }
            ?>
            <tr class="<?= $highlight ?>">
              <td data-sort="<?= $statusSort[$post['status']] ?>">
                <?= ucwords($post['status']) ?>
              </td>
              <td><?= $post['employee_name']?></td>
              <td><?= $post['check_out_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td><?= $post['check_in_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td>
                <?php if(count($post['computer']) > 0): ?>
                  <a href="<?= $this->Url->build('/inventory/moreInfo/' . $post['computer'][0]['id'])?>">
                    <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['computer'][0]['ComputerName'] ?>
                  </a>
                <?php else: ?>
                  <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['device']['name'] ?>
                <?php endif ?>
              </td>
              <td align="right">
                <?php if(count($post['computer']) == 0): ?>
                <a href="<?= $this->Url->build('/checkout/approve/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
                <?php else : ?>
                  <?php if($post['computer'][0]['IsCheckedOut'] == 'true' && $post['status'] == 'active'): ?>
                <a href="<?= $this->Url->build('/checkout/device/in/' . $post['id'] . '/' . $post['computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-secondary" title="Check In Device">
                  <i class="mdi mdi-cart-remove icon-sm icon-inline text-white-50"></i>
                </a>
                <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/extend_checkout/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Extend Check Out">
                  <i class="mdi mdi-calendar-expand-horizontal icon-sm icon-inline text-white-50"></i>
                </a>
                  <?php elseif ($post['computer'][0]['IsCheckedOut'] == 'false'): ?>
                <a href="<?= $this->Url->build('/checkout/device/out/' . $post['id'] . '/' . $post['computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Check Out Device">
                  <i class="mdi mdi-cart-check icon-sm icon-inline text-white-50"></i>
                </a>
                  <?php endif; ?>
                <?php endif ?>
                <?php if(in_array($post['status'], array('new', 'approved'))): ?>
                <a href="<?= $this->Url->build('/checkout/deny/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
                <?php endif; ?>
                <?php if($post['status'] == 'denied'): ?>
                <a href="<?= $this->Url->build('/checkout/delete/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-request" title="Delete"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="col-xl-12">
    <div class="card border-left-primary shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Request List</h6>
      </div>
      <div class="card-body">
        <?php if(count($upcoming) > 0): ?>
        <table class="table table-striped">
          <thead>
            <th>Status</th>
            <th>Employee Name</th>
            <th>Check Out Date</th>
            <th>Check In Date</th>
            <th>Device</th>
            <th></th>
          </thead>
          <tbody>
            <?php foreach ($upcoming as $post): ?>
            <tr>
              <td data-sort="<?= $statusSort[$post['status']] ?>">
                <?= ucwords($post['status']) ?>
              </td>
              <td><?= $post['employee_name']?></td>
              <td><?= $post['check_out_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td><?= $post['check_out_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td>
                <?php if(count($post['computer']) > 0): ?>
                  <a href="<?= $this->Url->build('/inventory/moreInfo/' . $post['computer'][0]['id'])?>">
                    <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['computer'][0]['ComputerName'] ?>
                  </a>
                <?php else: ?>
                  <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['device']['name'] ?>
                <?php endif ?>
              </td>
              <td align="right">
                <?php if(count($post['computer']) == 0): ?>
                <a href="<?= $this->Url->build('/checkout/approve/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
                <?php elseif ($post['computer'][0]['IsCheckedOut'] == 'false'): ?>
                <a href="<?= $this->Url->build('/checkout/device/out/' . $post['id'] . '/' . $post['computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Check Out Device">
                  <i class="mdi mdi-cart-check icon-sm icon-inline text-white-50"></i>
                </a>
                <?php endif ?>
                <?php if(in_array($post['status'], array('new', 'approved'))): ?>
                <a href="<?= $this->Url->build('/checkout/deny/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
                <?php endif; ?>
                <?php if($post['status'] == 'denied' || $this->Time->isPast($post['check_in_date'])): ?>
                <a href="<?= $this->Url->build('/checkout/delete/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-request" title="Delete"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p align="center">There are no checkout requests new, active, or pending at this time. </p>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>
