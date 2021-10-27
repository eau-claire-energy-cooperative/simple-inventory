<!DOCTYPE html>
  <html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title_for_layout ?> | Computer Inventory Manager</title>

    <?php

      echo $this->Html->css('material-icons');
      echo $this->Html->css('sb-admin-2');

      echo $this->fetch('meta');
      echo $this->fetch('css');
    ?>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  </head>
<body id="page-top">


  <!-- Content Wrapper -->
  <div id="content-wrapper">

      <!-- Begin Page Content -->
      <div style="max-width:600px;">

        <!-- Main Content -->
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>

      </div>
  </div>

  <?php
    //put the scripts at the bottomg
    echo $this->Html->script("jquery.min.js");
    echo $this->Html->script("bootstrap.min.js");
    echo $this->Html->script("bootstrap.bundle.min.js");

    echo $this->fetch('script');
  ?>
  </body>
</html>
