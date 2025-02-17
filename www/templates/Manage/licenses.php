<?= $this->Html->css('dataTables.bootstrap4.min', ['block'=>'css']) ?>
<?= $this->Html->script(["jquery.dataTables.min.js", "dataTables.bootstrap4.min.js"], ['block'=>'script']) ?>
<?=
  $this->Html->scriptBlock("$(document).ready(function() {
      $('#dataTable').DataTable({
        paging: true,
        pageLength: 50,
        stateSave: true,
        dom: '<\"top\"ifp>rt<\"bottom\"p>',
        language: {
          'search': 'Filter:'
          }
        });
   });", ["block"=>true])
?>

<div class="mb-4" align="right">
  <a href="<?= $this->Url->build('/manage/edit_license') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add License</a>
</div>

<div class="card shadow mb-4">
  <div class="card-body">
    <?php if(count($licenses) == 0): ?>
    <p align="center">Add licenses for software you need to track. Click <b>Add License</b> above to get started.</p>
    <?php endif; ?>
    <table id="dataTable" class="table table-striped">
      <thead>
        <th>License Name</th>
        <th>Vendor</th>
        <th>License Keys</th>
        <th>Expiration</th>
      </thead>
    	<?php foreach($licenses as $aLicense): ?>
      <?php $sort_date = ($this->License->hasExpiration($aLicense) ? $aLicense->ExpirationDate->i18nFormat('yyyyMMdd') : 0) ?>
    	<tr>
    		<td><?= $this->Html->link($aLicense['LicenseName'], '/manage/view_license/' . $aLicense['id']) ?></td>
    		<td><?= $aLicense['Vendor'] ?></td>
        <td><?= count($aLicense['license_key']) ?></td>
        <td data-sort="<?= $sort_date ?>" class="<?= $this->License->expirationCSS($aLicense['ExpirationDate'], $aLicense['StartReminder']) ?>">
          <?php if($this->License->hasExpiration($aLicense)): ?>
            <?= $aLicense->ExpirationDate->i18nFormat('MM/dd/YY') ?>
          <?php endif ?>
        </td>
    	</tr>
    	<?php endforeach ?>
    </table>
  </div>
</div>
