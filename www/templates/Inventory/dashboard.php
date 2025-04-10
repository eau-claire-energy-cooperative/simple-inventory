<script type="text/javascript">
var dataTable = null;
</script>
<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      dataTable = $('#dataTable').DataTable({
        paging: true,
        pageLength: 10,
        stateSave: true,
        stateDuration: 60,
        order: [
          [-1, 'desc']
        ],
        layout: {
          top2Start: 'info',
          top2End: {
            search: {}
          },
          topStart: null,
          topEnd: {
            paging: {
              type: 'simple_numbers'
            }
          },
          bottomStart: null,
          bottomEnd: {
            paging: {
              type: 'simple_numbers'
            }
          }
        },
        language: {
          search: 'Filter:',
          paginate: {
            next: 'Next',
            previous: 'Previous'
          }
        }
      });
   });", ["block"=>true])
?>

<div class="row">
  <div class="col-xl-12 col-md-12 mb-4">
    <div class="row">
      <div class="col-lg-3">
        <div class="card shadow h-500 py-1">
          <a href="<?= $this->Url->build('/manage/device_types') ?>" class="link-variant">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Device Types</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_types ?></div>
                </div>
                <div class="col-auto">
                  <i class="mdi mdi-devices icon-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card shadow h-500 py-1">
          <a href="<?= $this->Url->build('/inventory/computer_inventory') ?>" class="link-variant">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Devices</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_devices ?></div>
                </div>
                <div class="col-auto">
                  <i class="mdi mdi-monitor-cellphone-star icon-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card shadow h-500 py-1">
          <a href="<?= $this->Url->build('/inventory/applications') ?>" class="link-variant">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Unique Applications</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_applications ?></div>
                </div>
                <div class="col-auto">
                  <i class="mdi mdi-application-cog-outline icon-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <?php $lifecycleDue = $this->Lifecycle->countDue($lifecycles); ?>
      <div class="col-lg-3">
        <div class="card <?= ($lifecycleDue > 0) ? 'border-left-danger' : '' ?> shadow h-500 py-1">
          <a href="<?= $this->Url->build('/applications/lifecycle') ?>" class="link-variant">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Lifecycle Checks Required</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $lifecycleDue ?></div>
                </div>
                <div class="col-auto">
                  <i class="mdi mdi-calendar-check icon-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- checkout requests -->
<div class="row">
  <?php if(count($new_checkout) > 0): ?>
  <div class="col-xl-12">
    <div class="card border-left-warning shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">New Checkout Requests</h6>
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
            <?php foreach ($new_checkout as $post): ?>
            <tr>
              <td><?= ucwords($post['status']) ?></td>
              <td><?= $post['employee_name']?></td>
              <td><?= $post['check_out_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td><?= $post['check_in_date']->i18nFormat('MM/dd/yyy') ?></td>
              <td>
                <i class="mdi mdi-<?= $post['device']['icon'] ?> icon-sm icon-inline"></i> <?= $post['device']['name'] ?>
              </td>
              <td align="right">
                <a href="<?= $this->Url->build('/checkout/approve/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
                <a href="<?= $this->Url->build('/checkout/deny/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if(count($expired_checkout) > 0): ?>
  <div class="col-xl-12">
    <div class="card border-left-danger shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Past Due Devices</h6>
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
            <?php foreach ($expired_checkout as $post): ?>
            <tr>
              <td>
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
                <?php if($post['computer'][0]['IsCheckedOut'] == 'true' && $post['status'] == 'active'): ?>
                <a href="<?= $this->Url->build('/checkout/device/in/' . $post['id'] . '/' . $post['computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-secondary" title="Check In Device">
                  <i class="mdi mdi-cart-remove icon-sm icon-inline text-white-50"></i>
                </a>
                <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?= $this->Url->build('/ajax/extend_checkout/' . $post['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Extend Check Out">
                  <i class="mdi mdi-calendar-expand-horizontal icon-sm icon-inline text-white-50"></i>
                </a>
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
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
  </div>
  <div class="card-body">
    <table class="table table-striped" id="dataTable">
    	<thead>
        <tr>
            <th>Message</th>
            <th>User</th>
            <th>Date</th>
        </tr>
    	</thead>
    	<tbody>
        <?php foreach ($logs as $aLog): ?>
        <tr>
            <td><?= $aLog['MESSAGE'] ?></td>
            <td><?= $aLog['USER'] ?></td>
            <td data-sort="<?= $aLog['DATED']->format('U') ?>"><?= $aLog['DATED']->format('m/d/Y H:i') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</div>
