<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $title_for_layout ?> | Simple Inventory Manager</title>

  <?php

    echo $this->Html->script("jquery.min.js");
    echo $this->Html->script("bootstrap.min.js");
    echo $this->Html->script("bootstrap.bundle.min.js");
    echo $this->Html->script("jquery.easing.min.js");

    echo $this->Html->css('material-icons');
    echo $this->Html->css('sb-admin-2');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
  ?>

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

  <!-- Main Content -->
  <?php echo $this->Session->flash(); ?>
  <?php echo $this->fetch('content'); ?>

  </div>

  <?php
    //put this at the bottom so it loads right
    echo $this->Html->script("sb-admin-2.min.js");
  ?>

</body>

</html>
