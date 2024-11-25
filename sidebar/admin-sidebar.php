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
                <li class="nav-item <?= ($current_page == 'manage_event.php') ? 'active' : '' ?>">
                    <a href="manage_event.php">
                        <i class="fas fa-calendar"></i>
                        <p>Manage Event</p>
                    </a>
                </li>
                <li class="nav-item <?= ($current_page == 'users.php') ? 'active' : '' ?>">
                    <a href="users.php">
                        <i class="fas fa-users"></i>
                        <p>Manage Users</p>
                    </a>
                </li>
                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts" class="collapsed" aria-expanded="false">
                        <i class="fas fa-folder"></i>
                        <p>Report</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= ($current_page == 'admin-logs.php' || $current_page == 'user-logs.php') ? 'show' : '' ?>" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="nav-item <?= ($current_page == 'admin-logs.php') ? 'active' : '' ?>">
                                <a href="admin-logs.php">
                                    <i class="fas fa-file"></i>
                                    <p>Admin Logs</p>
                                </a>
                            </li>
                            <li class="nav-item <?= ($current_page == 'user-logs.php') ? 'active' : '' ?>">
                                <a href="user-logs.php">
                                    <i class="fas fa-file"></i>
                                    <p>Users Logs</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->