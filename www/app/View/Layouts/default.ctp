<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout ?> | Computer Inventory Manager
	</title>
	<?php
	
		echo $this->Html->script("jquery-1.7.2.js");

		echo $this->Html->css('Main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>
<body>
	<div id="container">
		<div id="header">
			<ul>
				<li><?php echo $this->Html->link('Scheduled Tasks','/admin/commands') ?></li>
				<li><?php echo $this->Html->link('Licenses', '/admin/licenses') ?></li>
				<li><?php echo $this->Html->link('Programs','/admin/restricted_programs') ?></li>
				<li><?php echo $this->Html->link('Decomissioned','/inventory/decommission') ?></li>
				<li><?php echo $this->Html->link('Inventory','/inventory/') ?></li>
			</ul>
		</div>
		<div id="content">

			<h1><?php echo $title_for_layout ?></h1>
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php 
				if(file_exists(WWW_ROOT . '/drivers/double_drivers.zip')){
					echo "Utilities: ";
					echo $this->Html->link('Double Drivers', '/drivers/double_drivers.zip'); 
				}
			?>
			<div style="float: right;">
			<?php echo $this->Html->link('Logout', array('controller'=>'inventory','action' => 'logout')); ?> | 
			<?php echo $this->Html->link('Admin', array('controller'=>'admin','action' => 'index')); ?>
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
