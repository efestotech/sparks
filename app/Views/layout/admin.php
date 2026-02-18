<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> | <?= lang('Sparks.forge_brand') ?></title>

    <!-- Favicon SVG -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">
    
    <!-- Google Font: Inter & Cinzel for Titles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style: AdminLTE (using CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        :root {
            --sparks-blue: #0d5aba;
            --sparks-accent: #34495e;
            --sparks-orange: #e67e22;
            --marble: #fcfaf7;
            --stone: #24292e;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--marble);
        }

        .main-header {
            border-bottom: 3px solid var(--sparks-blue) !important;
            background-color: white !important;
        }

        .brand-link {
            border-bottom: 2px solid var(--sparks-blue) !important;
            background-color: var(--stone) !important;
            padding: 0.8125rem 0.5rem !important;
        }

        .brand-text {
            font-family: 'Inter', sans-serif;
            letter-spacing: 1px;
            font-weight: 700 !important;
            color: #fff !important;
            text-transform: uppercase;
        }

        .logo-svg {
            width: 33px;
            margin-right: 10px;
            vertical-align: middle;
            filter: drop-shadow(0 0 5px rgba(197, 160, 33, 0.5));
        }

        .main-sidebar {
            background-color: var(--stone) !important;
            box-shadow: 4px 0 10px rgba(0,0,0,0.2) !important;
        }

        [class*="sidebar-dark-"] .nav-sidebar > .nav-item.menu-open > .nav-link, 
        [class*="sidebar-dark-"] .nav-sidebar > .nav-item:hover > .nav-link, 
        [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link:focus {
            background-color: var(--sparks-accent) !important;
            color: #fff !important;
        }

        .nav-link.active {
            background-color: var(--sparks-blue) !important;
            color: #fff !important;
            box-shadow: 0 4px 6px rgba(13, 90, 186, 0.3) !important;
        }

        .content-header h1 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: var(--stone);
            position: relative;
            display: inline-block;
        }

        .content-header h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--sparks-blue);
        }

        .card {
            border-radius: 4px;
            border-top: 4px solid var(--sparks-blue);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .btn-primary {
            background-color: var(--sparks-blue);
            border-color: var(--sparks-blue);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            background-color: #0b4a9b;
            border-color: #0b4a9b;
        }

        .badge-success { background-color: #2d6a4f; }
        .badge-info { background-color: var(--greek-blue); }

        .main-footer {
            border-top: none !important;
            background-color: #fff !important;
            padding: 1.5rem 1rem !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <!-- Language Switcher -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-globe mr-1"></i>
                    <span class="text-uppercase font-weight-bold small"><?= service('request')->getLocale() ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0" style="border-radius: 4px; border: 1px solid var(--sparks-blue);">
                    <a href="?lang=it" class="dropdown-item <?= service('request')->getLocale() == 'it' ? 'active bg-warning' : '' ?>">
                        IT - Italiano
                    </a>
                    <a href="?lang=en" class="dropdown-item <?= service('request')->getLocale() == 'en' ? 'active bg-warning' : '' ?>">
                        EN - English
                    </a>
                </div>
            </li>
            
            <li class="nav-item d-none d-sm-inline-block px-3">
                <span class="nav-link text-muted font-italic">Efesto Tech di Marco Spinelli</span>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('admin/logout') ?>" title="<?= lang('Sparks.exit_forge') ?>">
                    <i class="fas fa-power-off"></i>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url('admin') ?>" class="brand-link">
            <svg class="logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="60" width="60" height="20" fill="#c5a021" />
                <path d="M10 80H90V90H10V80Z" fill="#b3542d" />
                <path d="M30 30L50 10L70 30L50 50L30 30Z" fill="#c5a021" />
                <path d="M45 40L55 40L60 60L40 60L45 40Z" fill="#777" />
                <path d="M50 0V20M80 30L100 20M30 60L10 50" stroke="#fcfaf7" stroke-width="3" stroke-linecap="round" />
            </svg>
            <span class="brand-text font-weight-light">SPARKS</span>
        </a>

        <div class="sidebar">
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
            <li class="nav-item">
                <a href="<?= base_url('admin') ?>" class="nav-link <?= (url_is('admin')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p><?= lang('Sparks.forge_dashboard') ?></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/queue/new') ?>" class="nav-link <?= (url_is('admin/queue/new')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-paper-plane"></i>
                    <p><?= lang('Sparks.ignite_spark') ?></p>
                </a>
            </li>
            <li class="nav-header text-muted small"><?= lang('Sparks.armory_tools') ?></li>
            <li class="nav-item">
                <a href="<?= base_url('admin/smtp') ?>" class="nav-link <?= (url_is('admin/smtp*')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-server"></i>
                    <p><?= lang('Sparks.olympus_servers') ?></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/queue') ?>" class="nav-link <?= (url_is('admin/queue*') && !url_is('admin/queue/new')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-list"></i>
                    <p><?= lang('Sparks.message_scroll') ?></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/logs') ?>" class="nav-link <?= (url_is('admin/logs*')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-history"></i>
                    <p><?= lang('Sparks.chronicle_logs') ?></p>
                </a>
            </li>
            <li class="nav-header text-muted small"><?= lang('Sparks.citadel_control') ?></li>
            <li class="nav-item">
                <a href="<?= base_url('admin/users') ?>" class="nav-link <?= (url_is('admin/users*')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-user-shield"></i>
                    <p><?= lang('Sparks.guardians') ?></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= (url_is('admin/settings*')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-cog"></i>
                    <p><?= lang('Sparks.global_laws') ?></p>
                </a>
            </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= $this->renderSection('header') ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Forge version 1.0.0
        </div>
        <strong>&copy; <?= date('Y') ?> <a href="https://www.efestotech.it" class="text-dark">Efesto Tech di Marco Spinelli</a>.</strong> <?= lang('Sparks.rights_reserved') ?>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
