<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img
                    src="../assets/img/kaiadmin/logo_light.svg"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>

                <li class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'event.php') ? 'active' : '' ?>">
                    <a href="event.php">
                        <i class="fas fa-calendar-check"></i>
                        <p>Event List</p>
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'my-event.php') ? 'active' : '' ?>">
                    <a href="my-event.php">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Event Summary</p>
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'logs.php') ? 'active' : '' ?>">
                    <a href="logs.php">
                        <i class="fas fa-file"></i>
                        <p>Logs</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->