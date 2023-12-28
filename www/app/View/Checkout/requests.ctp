<?php
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
        [2, 'asc']
      ],
      columnDefs: [
        {'searchable': false, 'targets': [-1]}
      ]
    })});", array("inline"=>false));
?>
<div class="card shadow mb-4">
  <div class="card-body">
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>Approved</th>
        <th>Employee Name</th>
        <th>Check Out Date</th>
        <th>Check In Date</th>
        <th>Device</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($checkout as $post): ?>
        <tr>
          <td>
            <?php if(count($post['Computer']) > 0): ?>
              Yes
            <?php else: ?>
              No
            <?php endif ?>
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
              <?php echo $post['DeviceType']['name'] ?>
            <?php endif ?>
          </td>
          <td align="right">
            <?php if(count($post['Computer']) == 0): ?>
            <a href="<?php echo $this->Html->url('/checkout/approve/' . $post['CheckoutRequest']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-success" title="Approve"><i class="mdi mdi-thumb-up icon-sm icon-inline text-white-50"></i></a>
            <?php else : ?>
            <a href="" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-primary">
              <i class="mdi mdi-cart-check icon-sm icon-inline text-white-50"></i>
            </a>
            <?php endif ?>
            <a href="<?php echo $this->Html->url('/checkout/deny/' . $post['CheckoutRequest']['id']) ?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm mr-2 btn-danger" title="Deny"><i class="mdi mdi-thumb-down icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
