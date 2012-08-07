
<?php echo $this->Html->link('Home', '/'); ?>
	 
<div id="logDiv">
   <table>
    <?php foreach ($logs as $post): ?>
    <tr>
        <td> <?php echo  $post['Logs']['id']; ?></td>
        <td> <?php echo  $this->Time->nice( $post['Logs']['DATED']) ; ?></td>
        <td> <?php echo  $post['Logs']['LOGGER']; ?></td>
        <td> <?php echo  $post['Logs']['LEVEL']; ?></td>
        <td> <?php echo  $post['Logs']['MESSAGE']; ?></td>
    </tr>
    
    <?php endforeach; ?>
   </table>
</div> 
