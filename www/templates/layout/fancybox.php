<html>
  <head>
    <?= $this->Html->css(['sb-admin-2', 'materialdesignicons.min', 'icons']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  </head>
  <body id="page-top">
    <!-- Content Wrapper -->
    <div id="content-wrapper">

        <!-- Begin Page Content -->
        <div style="max-width:600px;">

          <!-- Main Content -->
          <?= $this->Flash->render(); ?>
          <?= $this->fetch('content'); ?>

        </div>
    </div>

    <?= $this->Html->script(["jquery.min.js", "bootstrap.bundle.min.js", "jquery.easing.min.js", "sb-admin-2.min.js"]) ?>
    <?= $this->fetch('script') ?>
  </body>
</html>
