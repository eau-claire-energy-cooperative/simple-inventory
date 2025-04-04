<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
  //script to load the confirmation dialog
  $this->Html->scriptBlock("$(document).ready(function() {

      $('a.delete-file').confirm({
        content: 'Are you sure you want to delete this file?',
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

<div class="card shadow mb-4">
  <div class="card-body">
    <?php if(count($drivers) > 0): ?>
    <p>These files are in the <code>/drivers/</code> directory. Deleting them will remove them from the file system completely.</p>
    <table class="table table-striped">
      <thead>
        <th>Filename</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach($drivers as $file): ?>
        <tr>
          <td><?= $file ?></td>
          <td align="right">
            <a href="<?= $this->Url->build(sprintf("/drivers/%s", $file)) ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2"><i class="mdi mdi-download icon-sm icon-inline text-white-50"></i></a>
            <a data-title="Delete File" href="<?= $this->Url->build(sprintf('/admin/delete_driver?file=%s', $file)) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-file"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i></a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php else: ?>
      <p align="center">There are no files in the <code>/drivers/</code> directory.</p>
    <?php endif;?>
  </div>
</div>
