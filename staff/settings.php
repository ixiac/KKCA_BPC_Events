<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM staff WHERE SFID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["CID"];

    $active = 'settings';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <style>
        input:focus, textarea:focus, select:focus {
            border: 1px solid #203b70 !important;
            outline: none;
            box-shadow: 0 0 5px rgba(32, 59, 112, 0.5);
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index" class="logo">
                            <img
                                src="assets/img/kaiadmin/logo_light.svg"
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
                <?php include("partial/navbar.php"); ?>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="row">
                        <div class="col me-2 d-flex pt-2 pb-4">
                            <a href="home"
                                style="font-size: 20px; margin-top: 3px; color: gray;">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            <h3 class="fw-bold mb-3 ms-3">Settings</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-round">
                                <div class="card-body ps-5 pe-5">
                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-6 form-group">
                                            <label for="fname">First Name</label>
                                            <input type="text" class="form-control" id="fname" value="<?= htmlspecialchars($row['fname']) ?>" readonly>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="lname">Last Name</label>
                                            <input type="text" class="form-control" id="lname" value="<?= htmlspecialchars($row['lname']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($row['username']) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Profile</label><br>
                                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password" readonly>
                                    </div>
                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-8 form-group">
                                            <label for="email">Email Address</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" readonly>
                                                <span class="input-group-text" id="basic-addon2">@gmail.com</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="age">Age</label>
                                            <input type="text" class="form-control" id="age" value="<?= htmlspecialchars($row['age'] ?? '') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-profile"  style="height: 483px;">
                                <div class="card-header" style="border-bottom: none; height: 135px;">
                                    <div class="profile-picture">
                                        <div class="avatar avatar-xxl mb-3" style="margin-right: 4.2em;">
                                            <img src="../assets/img/myfavgayman.jpg" alt="..." class="avatar-img rounded-circle"
                                            style="height: 12em; width: 12em;">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="user-profile text-center mt-5">
                                        <div class="h1"><?= htmlspecialchars($row['username']) ?>, <?= htmlspecialchars($row['age'] ?? '') ?></div>
                                        <div class="job h4"><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></div>
                                        <div class="desc h4"><?= htmlspecialchars($row['email']) ?></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row user-stats text-center">
                                        <div class="col">
                                            <div class="number">125</div>
                                            <div class="title">Events Added</div>
                                        </div>
                                        <div class="col">
                                            <div class="number">101</div>
                                            <div class="title">Events Done</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    </div>
    <?php include("partial/script.php"); ?>
</body>

</html>
