<p><?php echo $this->Html->link('Home', '/'); ?> | <?php echo $this->Html->link('Add User', '/admin/editUser'); ?> </p>

<table>
  <?php foreach ($users as $aUser): ?>
    <tr>
    	<td><?php echo $aUser['User']['name'] ?></td>
    	<td><?php echo $aUser['User']['username'] ?></td>
        <td><?php echo $this->Html->link('Edit', array('action' => 'editUser', $aUser['User']['id'])); ?> 
        	<?php echo $this->Html->link("Delete","/admin/editUser/". $aUser['User']['id'] ."?action=delete") ?></td>

    </tr>
  <?php endforeach; ?>
</table>