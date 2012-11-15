<p><?php echo $this->Html->link('Home', '/'); ?></p>
<ul>
	<li><?php echo $this->Html->link('Users',array('action'=>'users')) ?></li>
	<li><?php echo $this->Html->link('Edit Locations', array('action' => 'location')); ?></li> 
	<li><?php echo $this->Html->link('Settings',array('action'=>'settings')) ?></li>
	<li><?php echo $this->Html->link('Scheduled Commands',array('action'=>'commands')) ?></li>
	<li><?php echo $this->Html->link('Logs', array('action' => 'logs')); ?></li>
</ul>
