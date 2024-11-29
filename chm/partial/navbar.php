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
                    $user_id = $_SESSION['id'];
                    
                    $approvedQuery = "SELECT * FROM appointment WHERE status = '1' AND event_by = '$user_id' LIMIT 10";
                    $approvedResult = mysqli_query($conn, $approvedQuery);

                    $totalApprovedQuery = "SELECT COUNT(*) AS total FROM appointment WHERE status = '1' AND event_by = '$user_id'";
                    $totalApprovedResult = mysqli_query($conn, $totalApprovedQuery);
                    $totalApprovedRow = mysqli_fetch_assoc($totalApprovedResult);
                    $totalApprovedCount = $totalApprovedRow['total'];
                    ?>
                    <?php if ($totalApprovedCount > 0): ?>
                        <span class="notification"><?= $totalApprovedCount ?></span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">
                            <?= $totalApprovedCount > 0 ? "You have $totalApprovedCount approved appointments" : "No new notifications" ?>
                        </div>
                    </li>
                    <?php if ($totalApprovedCount > 0): ?>
                        <li>
                            <div class="notif-center">
                                <?php while ($appointment = mysqli_fetch_assoc($approvedResult)): ?>
                                    <a href="history" class="notif-link">
                                        <div class="notif-icon notif-success">
                                            <i class="fa fa-check"></i>
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
                    <?php endif; ?>
                    <?php if ($totalApprovedCount > 10): ?>
                        <li>
                            <a class="see-all" href="appointment_history.php">See all approved appointments<i class="fa fa-angle-right"></i></a>
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
