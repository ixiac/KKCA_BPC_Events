<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom shadow">
    <div class="container-fluid">

        <img src="../assets/img/BPC-logo.png" alt="Logo 2" class="logo me-2" height="55" />
        <p class="my-2 fs-3"><b>Event Portal</b></p>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret submenu">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">
                            You have 4 new notification
                        </div>
                    </li>
                    <li>
                        <div class="scroll-wrapper notif-scroll scrollbar-outer" style="position: relative;">
                            <div class="notif-scroll scrollbar-outer scroll-content" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 244px;">
                                <div class="notif-center submenu">
                                    <a href="#">
                                        <div class="notif-icon notif-primary">
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block"> New user registered </span>
                                            <span class="time">5 minutes ago</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-success">
                                            <i class="fa fa-comment"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">
                                                Rahmad commented on Admin
                                            </span>
                                            <span class="time">12 minutes ago</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-img">
                                            <img src="assets/img/profile2.jpg" alt="Img Profile">
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">
                                                Reza send messages to you
                                            </span>
                                            <span class="time">12 minutes ago</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-danger">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block"> Farrah liked Admin </span>
                                            <span class="time">17 minutes ago</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="scroll-element scroll-x">
                                <div class="scroll-element_outer">
                                    <div class="scroll-element_size"></div>
                                    <div class="scroll-element_track"></div>
                                    <div class="scroll-bar" style="width: 0px;"></div>
                                </div>
                            </div>
                            <div class="scroll-element scroll-y">
                                <div class="scroll-element_outer">
                                    <div class="scroll-element_size"></div>
                                    <div class="scroll-element_track"></div>
                                    <div class="scroll-bar" style="height: 0px;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="<?= htmlspecialchars(empty($row['profile']) ? 'uploads/default_icon.png' : $row['profile']) ?>" alt="Profile Icon" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="fw-bold"><?= htmlspecialchars($row['username']) ?></span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="<?= htmlspecialchars(empty($row['profile']) ? 'uploads/default_icon.png' : $row['profile']) ?>" alt="Profile Image" class="avatar-img rounded" />
                                </div>
                                <div class="u-text">
                                    <h4><?= htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) ?></h4>
                                    <p class="text-muted"><?= htmlspecialchars($row['email']) ?></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="settings.php">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>