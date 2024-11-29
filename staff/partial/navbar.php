<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom shadow">
    <div class="container-fluid">

        <img src="../assets/img/BPC-logo.png" alt="Logo 2" class="logo me-2" height="55" />
        <p class="my-2 fs-3"><b>Event Portal</b></p>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <!-- Notification Dropdown -->
            <li class="nav-item topbar-icon dropdown hidden-caret submenu">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <?php
                    // Fetch pending appointments
                    $pendingQuery = "SELECT * FROM appointment WHERE status = '0' LIMIT 10";
                    $pendingResult = mysqli_query($conn, $pendingQuery);
                    $totalPendingQuery = "SELECT COUNT(*) AS total FROM appointment WHERE status = '0'";
                    $totalPendingResult = mysqli_query($conn, $totalPendingQuery);
                    $totalPendingRow = mysqli_fetch_assoc($totalPendingResult);
                    $totalPendingCount = $totalPendingRow['total'];
                    ?>
                    <span class="notification"><?= $totalPendingCount > 0 ? $totalPendingCount : '' ?></span>
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">
                            <?= $totalPendingCount > 0 ? "You have $totalPendingCount new pending appointments" : "No new notifications" ?>
                        </div>
                    </li>
                    <li>
                        <div class="notif-center">
                            <?php while ($appointment = mysqli_fetch_assoc($pendingResult)): ?>
                                <a href="history" class="notif-link">
                                    <div class="notif-icon notif-primary">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">
                                            <?= htmlspecialchars($appointment['event_name']) ?>
                                        </span>
                                        <span class="time">
                                            <?= date('F d, Y h:i A', strtotime($appointment['start_date'])) ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </li>
                    <?php if ($totalPendingCount > 10): ?>
                        <li>
                            <a class="see-all" href="history.php">See all appointments<i class="fa fa-angle-right"></i></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <!-- Profile Dropdown -->
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
                            <a class="dropdown-item" href="settings">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
