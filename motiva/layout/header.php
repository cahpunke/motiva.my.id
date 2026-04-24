<?php
use Config\Auth;

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$pageTitle = $pageTitle ?? 'MOTIVA Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> - MOTIVA</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/OverlayScrollbars.min.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">User Account</span>
                        <div class="dropdown-divider"></div>
                        <a href="profile.php" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="settings.php" class="dropdown-item">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <img src="https://via.placeholder.com/50" alt="MOTIVA Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><strong>MOTIVA</strong></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/160" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="profile.php" class="d-block"><?= htmlspecialchars($user['username']) ?></a>
                        <small class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                            <?= htmlspecialchars($user['role']) ?>
                        </small>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="map.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'map.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Peta Cagar Budaya</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="cagar.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'cagar.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-landmark"></i>
                                <p>Data Cagar Budaya</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-folder-open"></i>
                                <p>
                                    Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="import.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'import.php' ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Import Data</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="upload.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'upload.php' ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Upload Foto</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-header">ADMIN</li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan Sistem</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logs.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'logs.php' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Activity Log</p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-header">LAINNYA</li>
                        <li class="nav-item">
                            <a href="../index.php" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Lihat Website</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= htmlspecialchars($pageTitle) ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active"><?= htmlspecialchars($pageTitle) ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">