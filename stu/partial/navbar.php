<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom shadow">
    <div class="container-fluid">

        <img src="../assets/img/KKCA-logo.png" alt="Logo 1" class="logo me-2" height="60" />
        <img src="../assets/img/BPC-logo.png" alt="Logo 2" class="logo me-2" height="60" />
        <h2 class="mt-2"><b>Event Portal</b></h2>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="../assets/img/myfavgayman.jpg" alt="Profile Image" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="fw-bold"><?= htmlspecialchars($user['username']) ?></span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="../assets/img/myfavgayman.jpg" alt="Profile Image" class="avatar-img rounded" />
                                </div>
                                <div class="u-text">
                                    <h4><?= htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']) ?></h4>
                                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="settings.php">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
