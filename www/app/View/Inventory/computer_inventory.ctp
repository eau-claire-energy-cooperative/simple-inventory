<?php
    echo $this->Html->script("jquery.dataTables.min.js", false);
    echo $this->Html->script("dataTables.bootstrap4.min.js", false);

    echo $this->Html->css('dataTables.bootstrap4.min', array('inline'=>false));

    //script to load the datatable
    echo $this->Html->scriptBlock("$(document).ready(function() {
        $('#dataTable').DataTable({
          paging: true,
          pageLength: 50,
          stateSave: true,
          dom: '<\"top\"ifp>rt<\"bottom\"p>',
          language: {
            'search': 'Filter:'
            }
          });
     });", array("inline"=>false))
?>

<div class="mb-4" align="right">
  <a href="<?php echo $this->Html->url('/inventory/add') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50">add</i> Add Device</a>
  <a href="<?php echo $this->Html->url('/inventory/import') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="material-icons mi-sm mi-inline text-white-50">file_upload</i> Import Devices</a>
  <a href="<?php echo $this->Html->url('/search/listAll.csv') ?>" class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm"><i class="material-icons mi-sm mi-inline text-white-50">file_download</i> Download CSV</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <table class="table table-striped" id="dataTable">
    	<thead>
        <tr>
            <th><i class="material-icons">important_devices</i></th>
            <th>Device Name</th>
            <?php foreach($displayAttributes as $attribute): ?>
            <th><?php echo $columnNames[$attribute] ?></th>
            <?php endforeach; ?>
            <th>Location</th>
            <th>Last Update</th>
        </tr>
    	</thead>
    	<tbody>
        <?php foreach ($computer as $post): ?>
        <tr>
            <td data-sort="<?php echo $post['DeviceType']['name'] ?>">
              <a href="<?php echo $this->Html->url('/search/search/5/' . $post['DeviceType']['name']) ?>" class="icon-link"><i class="material-icons"><?php echo $post['DeviceType']['icon'] ?></i></a>
            </td>
            <td> <?php echo $this->Html->link( $post['Computer']['ComputerName'] , array('action' => 'moreInfo', $post['Computer']['id'])); ?></td>
            <?php foreach($displayAttributes as $attribute): ?>
            <td><?php echo $post['Computer'][$attribute] ?></td>
            <?php endforeach; ?>
            <td><?php echo $this->Html->link( $post['Location']['location'], array('controller'=>'search','action' => 'search', 0,$post['Computer']['ComputerLocation'])); ?></td>
            <td data-sort="<?php echo $this->Time->fromstring($post['Computer']['LastUpdated']) ?>"><?php echo $this->Time->format($post['Computer']['LastUpdated'],'%m/%d/%Y') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</div>
