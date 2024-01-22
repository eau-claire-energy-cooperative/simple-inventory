<?php
  $statusSort = array("new"=>0, "active"=>1, "approved"=>2, "denied"=>3);
  echo $this->Html->script("jquery-confirm.min.js",false);
  echo $this->Html->script("jquery.dataTables.min.js", false);
  echo $this->Html->script("dataTables.bootstrap4.min.js", false);

  echo $this->Html->css('jquery-confirm.min', array('inline'=>false));
  echo $this->Html->css('dataTables.bootstrap4.min', false);

  //script to load the table filter
  echo $this->Html->scriptBlock("$(document).ready(function() {
    $('#dataTable').DataTable({
      paging: true,
      pageLength: 50,
      search: {
        search: '" . $this->params['url']['q'] . "'
      },
      dom: '<\"top\"ifp>rt<\"bottom\"p>',
      language: {
        'search': 'Filter:'
      },
      order:[
        [0, 'asc'], [2, 'asc']
      ],
      columnDefs: [
        {'searchable': false, 'targets': [-1]}
      ]
    })});", array("inline"=>false));

    //script to load the confirmation dialog
    echo $this->Html->scriptBlock("$(document).ready(function() {

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
     });", array("inline"=>false))
?>
<?php



?>
<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/checkout/index') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> New Checkout Request</a>
</div>
<div class="card shadow mb-4">
  <div class="card-body">
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Status</th>
        <th>Employee Name</th>
        <th>Check Out Date</th>
        <th>Check In Date</th>
        <th>Device</th>
        <th></th>
      </thead>
      <tbody>
        <?php $now = time() ?>
        <?php foreach ($checkout as $post): ?>
        <?php
          // determine if the check in date has passed
          $highlight = "";
          if($post['CheckoutRequest']['status'] == 'active')
          {
            if($post['CheckoutRequest']['check_in_unix'] + 86400 < $now)
            {
              $highlight = "table-danger";
            }
            else
            {
              $highlight = "table-success";
            }
          }
        ?>
        <tr class="<?php echo $highlight ?>">
          <td data-sort="<?php echo $statusSort[$post['CheckoutRequest']['status']] ?>">
            <?php echo ucwords($post['CheckoutRequest']['status']) ?>
          </td>
          <td><?php echo $post['CheckoutRequest']['employee_name']?></td>
          <td data-sort="<?php echo $post['CheckoutRequest']['check_out_unix'] ?>"><?php echo $this->Time->format($post['CheckoutRequest']['check_out_date'], '%m/%d/%Y') ?></td>
          <td data-sort="<?php echo $post['CheckoutRequest']['check_in_unix'] ?>"><?php echo $this->Time->format($post['CheckoutRequest']['check_in_date'], '%m/%d/%Y') ?></td>
          <td>
            <?php if(count($post['Computer']) > 0): ?>
              <a href="<?php echo $this->Html->url('/inventory/moreInfo/' . $post['Computer'][0]['id'])?>">
                <i class="mdi mdi-<?php echo $post['DeviceType']['icon'] ?> icon-sm icon-inline"></i> <?php echo $post['Computer'][0]['ComputerName'] ?>
              </a>
            <?php else: ?>
              <i class="mdi mdi-<?php echo $post['DeviceType']['icon'] ?> icon-sm icon-inline"></i> <?php echo $post['DeviceType']['name'] ?>
            <?php endif ?>
          </td>
          <td align="right">
            <?php if(count($post['Computer']) == 0): ?>
            <a href="<?php echo $this->Html->url('/checkout/approve/' . $post['CheckoutRequest']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
            <?php else : ?>
              <?php if($post['Computer'][0]['IsCheckedOut'] == 'true' && $post['CheckoutRequest']['status'] == 'active'): ?>
            <a href="<?php echo $this->Html->url('/checkout/device/in/' . $post['CheckoutRequest']['id'] . '/' . $post['Computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-3 pl-4 pr-4 btn-secondary" title="Check In Device">
              <i class="mdi mdi-cart-remove icon-sm icon-inline text-white-50"></i>
            </a>
              <?php elseif ($post['Computer'][0]['IsCheckedOut'] == 'false'): ?>
            <a href="<?php echo $this->Html->url('/checkout/device/out/' . $post['CheckoutRequest']['id'] . '/' . $post['Computer'][0]['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary" title="Check Out Device">
              <i class="mdi mdi-cart-check icon-sm icon-inline text-white-50"></i>
            </a>
              <?php endif; ?>
            <?php endif ?>
            <?php if($post['CheckoutRequest']['status'] != 'denied' && (count($post['Computer']) == 0 || $post['Computer'][0]['IsCheckedOut'] == 'false')): ?>
            <a href="<?php echo $this->Html->url('/checkout/deny/' . $post['CheckoutRequest']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
            <?php endif; ?>
            <?php if($post['CheckoutRequest']['status'] == 'denied'): ?>
            <a href="<?php echo $this->Html->url('/checkout/delete/' . $post['CheckoutRequest']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger delete-request" title="Delete"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
