<?= $this->Html->script("jquery-confirm.min.js", ['block'=>'script']) ?>
<?= $this->Html->css('jquery-confirm.min') ?>
<?=
    //script to load the confirmation dialog
    $this->Html->scriptBlock("$(document).ready(function() {
        $('a.delete-location').confirm({
          content: 'Are you sure you want to delete this user?',
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
  <a href="<?= $this->Url->build(['controller' => 'Manage', 'action' => 'addDeviceType']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-plus icon-sm icon-inline text-white-50"></i> Add Type</a>
</div>
<div class="card shadow mb-4">
  <div class="card-body">
    <p>Device Types are used to assign attributes to specific types of device classes. Examples may be computers, phones, or printers. The icon class is the CSS class as indicated by the open source <a href="https://materialdesignicons.com/" target="_blank">Material Design Icons</a> library.
    If you can't find an icon you like consider using the basic <i>desktop_windows</i> icon.</p>

    <table class="table table-striped">
      <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Attributes</th>
        <th>Assets Assigned</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach ($device_types as $post): ?>
        <tr>
            <?php $attributes = explode(',', $post['attributes']); ?>
            <td><?= $post['name']; ?> <i class="mdi mdi-<?= $post['icon']?> icon-inline ml-2" ></i></td>
            <td><?= $post['slug'] ?></td>
            <td><?= count($attributes) ?></td>
            <td><?= $this->Html->link(count($post['computer']),'/search/search/5/' . $post['name']) ?></td>
              <td align="right">
    				    <a href="<?= $this->Url->build(['action' => 'editDeviceType', $post['id']]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"><i class="mdi mdi-square-edit-outline icon-sm icon-inline text-white-50"></i> Edit</a>
    				    <a data-title="Delete Type" href="<?= $this->Url->build(['action' => 'deleteDeviceType', $post['id']]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 delete-location"><i class="mdi mdi-delete icon-sm icon-inline text-white-50"></i> Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
