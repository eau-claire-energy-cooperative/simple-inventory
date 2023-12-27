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
    echo $this->Html->css('jquery.fancybox');
    echo $this->Html->css('materialdesignicons.min');
    echo $this->Html->css('icons');
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
          <i class="mdi mdi-monitor"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Simple Inventory</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Current Inventory -->
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('inventory',$active_menu) ?>">
        <a class="nav-link" href="<?php echo $this->Html->url('/') ?>">
          <i class="mdi mdi-table"></i>
          <span>Current Inventory</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - devices Collapse Menu -->
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('manage',$active_menu) ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="mdi mdi-devices"></i>
          <span>Devices</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Devices</h6>
            <?php if(isset($settings) && $settings['ldap_computers_basedn'] != ''): ?>
            <a class="collapse-item" href="<?php echo $this->Html->url('/inventory/active_directory_sync') ?>">Active Directory Sync</a>
            <?php endif; ?>
            <a class="collapse-item" href="<?php echo $this->Html->url('/inventory/decommission') ?>">Decommissioned</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/manage/deviceTypes') ?>">Device Types</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Checkout -->
      <?php if($settings['enable_device_checkout'] == 'true'): ?>
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('checkout',$active_menu) ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCheck" aria-expanded="true" aria-controls="collapseCheck">
          <i class="mdi mdi-cart-check"></i>
          <span>Device Checkout</span>
        </a>
        <div id="collapseCheck" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Checkout Management</h6>
            <a class="collapse-item" href="">Available Devices</a>
            <a class="collapse-item" href="">Checkout Requests</a>
          </div>
        </div>
      </li>
      <?php endif ?>

      <!-- Nav Item - Applications -->
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('applications',$active_menu) ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSoftware" aria-expanded="true" aria-controls="collapseSoftware">
          <i class="mdi mdi-application-cog-outline"></i>
          <span>Software</span>
        </a>
        <div id="collapseSoftware" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Software</h6>
            <a class="collapse-item" href="<?php echo $this->Html->url('/applications') ?>">Applications</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/applications/lifecycle')?>">Application Lifecycles</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/manage/licenses') ?>">Licenses</a>
            <a class="collapse-item" href="<?php echo $this->Html->url('/applications/operating_systems') ?>">Operating Systems</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item Scheduled Tasks -->
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('schedule',$active_menu) ?>">
        <a class="nav-link" href="<?php echo $this->Html->url('/manage/commands') ?>">
          <i class="mdi mdi-calendar"></i>
          <span>Scheduled Tasks</span></a>
      </li>

      <!-- Nav Item - Tools Collapse Menu -->
      <li class="nav-item <?php echo $this->Menu->getActiveMenu('admin',$active_menu) ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="mdi mdi-cog"></i>
          <span>Tools</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Admin Tools:</h6>
            <a class="collapse-item" href="<?php echo $this->Html->url('/admin/downloads') ?>">Downloads</a>
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
            <!--
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>

                <span class="badge badge-danger badge-counter">3+</span>
              </a>

              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Message Center
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
                <span class="mr-3 d-none d-lg-inline text-gray-600 small"><?php echo $this->Session->read('User.name') ?></span>
                <?php echo $this->Html->image($this->Menu->getProfileImage($this->Session->read('User.gravatar')),array('class'=>'img-profile rounded-circle')) ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a data-fancybox data-type="ajax" href="javascript:;" data-src="<?php echo $this->Html->url('/ajax/setProfileImage/') ?>" class="dropdown-item fancybox.ajax">
                  <i class="mdi mdi-account icon-sm mr-2 text-gray-400"></i>
                  Set Gravatar
                </a>
                <a class="dropdown-item" href="<?php echo $this->Html->url('/inventory/logout') ?>">
                  <i class="mdi mdi-logout icon-sm mr-2 text-gray-400"></i>
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
            <a href="<?php echo $this->Html->url('/admin/downloads');  ?>" class="mr-3 h6"><i class="mdi mdi-download-circle-outline icon-2x icon-inline"></i> Downloads </a>
            <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki" target="_blank" class="mr-3 h6"><i class="mdi mdi-information-outline icon-inline icon-2x"></i> Documentation</a>
            <a href="https://github.com/eau-claire-energy-cooperative/simple-inventory" class="h6"><i class="mdi mdi-github icon-inline icon-2x"></i> View Source</a>
          </div>
          <div class="text-center mt-1">
            <p>Version <?php echo Configure::read('Settings.version') ?></p>
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
    <i class="mdi mdi-chevron-up mdi-inline"></i>
  </a>

<?php
    //put the scripts at the bottom
    echo $this->Html->script("jquery.min.js");
    echo $this->Html->script("bootstrap.bundle.min.js");
    echo $this->Html->script("jquery.easing.min.js");
    echo $this->Html->script("sb-admin-2.min.js");
    echo $this->Html->script("jquery.fancybox.min.js",false);

    echo $this->fetch('script');
?>

</body>

</html>
