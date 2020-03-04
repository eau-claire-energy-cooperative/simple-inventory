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
	
    echo $this->Html->css('/fontawesome/css/all.min');
    echo $this->Html->css('sb-admin-2');

		echo $this->fetch('meta');
		echo $this->fetch('css');
  ?>
  
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $this->Html->url('/') ?>">
        <div class="sidebar-brand-icon">
          <i class="fas fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Simple Inventory</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Current Inventory -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $this->Html->url('/') ?>">
          <i class="fas fa-fw fa-table"></i>
          <span>Current Inventory</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - Manage Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Manage</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Inventory</h6>
            <?php if($settings['ldap_computers_basedn'] != ''): ?>
            <a class="collapse-item" href="<?php echo $this->Html->url('/inventory/active_directory_sync') ?>">Active Directory Sync</a>
            <?php endif; ?>
            <a class="collapse-item" href="<?php echo $this->Html->url('/manage/licenses') ?>">Licenses</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/inventory/restricted_programs') ?>">Programs</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - decom -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $this->Html->url('/inventory/decommission') ?>">
          <i class="fas fa-fw fa-ban"></i>
          <span>Decommissioned</span></a>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item Scheduled Tasks -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $this->Html->url('/inventory/commands') ?>">
          <i class="far fa-calendar-alt fa-fw"></i>
          <span>Scheduled Tasks</span></a>
      </li>

      <!-- Nav Item - Tools Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-cog"></i>
          <span>Tools</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Admin Tools:</h6>
            <?php if(file_exists(WWW_ROOT . '/drivers/double_drivers.zip')): ?>
            <?php echo $this->Html->link('Download Double Drivers', '/drivers/double_drivers.zip', array('class'=>'collapse-item')); ?>
            <?php endif; ?>
            <a class="collapse-item" href="<?php echo $this->Html->url('/admin/location') ?>">Edit Locations</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/admin/logs') ?>">Logs</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/admin/settings') ?>">Settings</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/admin/users') ?>">Users</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <h1 class="h3 ml-2 mb-0 text-gray-800"><?php echo $title_for_layout ?></h1>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
         
            <!-- Nav Item - Alerts 
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                
                <span class="badge badge-danger badge-counter">3+</span>
              </a>

              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
            </li>
            -->
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-3 d-none d-lg-inline text-gray-600 small"><?php echo $this->Session->read('username') ?></span>
                <?php echo $this->Html->image($this->ProfileImage->getImage($this->Session->read('username')),array('class'=>'img-profile rounded-circle')) ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo $this->Html->url('/inventory/logout') ?>">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Main Content -->
          <?php echo $this->Session->flash(); ?>
          <?php echo $this->fetch('content'); ?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory"><i class="fab fa-github fa-2x"></i></a>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

<?php
    //put the scripts at the bottomg
    echo $this->Html->script("jquery.min.js");
    echo $this->Html->script("bootstrap.min.js");
    echo $this->Html->script("bootstrap.bundle.min.js");
    echo $this->Html->script("jquery.easing.min.js");
    echo $this->Html->script("sb-admin-2.min.js");
    
    echo $this->fetch('script');
?>

</body>

</html>
