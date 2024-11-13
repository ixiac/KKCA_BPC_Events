<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header shadow" data-background-color="dark">
            <a href="home" class="logo" style="color: white;">
                <b>Overview</b>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item <?php if ($active == "home") { echo "active"; } ?>">
                    <a href="home">
                        <i class="fas fa-home"></i>
                        <p>Home Page</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section" style="color: #b9babf !important">Components</h4>
                </li>
                <li class="nav-item <?php if ($active == "history") { echo "active"; } ?>">
                    <a href="history">
                        <i class="fas fa-table"></i>
                        <p>Event History</p>
                    </a>
                </li>
                <li class="nav-item <?php if ($active == "settings") { echo "active"; } ?>">
                    <a href="settings">
                        <i class="fas fa-pen"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <li class="nav-item <?php if ($active == "about") { echo "active"; } ?>">
                    <a href="about">
                        <i class="fas fa-info"></i>
                        <p>About</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->