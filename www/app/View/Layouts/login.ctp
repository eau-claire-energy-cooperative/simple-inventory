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
    echo $this->Html->script("bootstrap.bundle.min.js");
    echo $this->Html->script("jquery.easing.min.js");

    echo $this->Html->css('sb-admin-2');
    echo $this->Html->css('materialdesignicons.min');
    echo $this->Html->css('icons');

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
  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-2">
        <div class="card-body p-0">
          <?php echo $this->fetch('content'); ?>
          <div class="row mt-4 pb-3">
            <div class="col-lg-12">
              <div class="copyright text-center my-auto">
                <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki" target="_blank" class="mr-3 h6"><i class="mdi mdi-information-outline icon-sm"> Documentation</i></a>
                <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory" class="h6"><i class="mdi mdi-github icon-sm"> View Source</i></a><br>
                Version <?php echo Configure::read('Settings.version') ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  //put this at the bottom so it loads right
  echo $this->Html->script("sb-admin-2.min.js");
?>

</body>

</html>
