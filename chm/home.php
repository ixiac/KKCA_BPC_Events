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

    $active = 'home';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>
</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>

        <div class="main-panel">
            
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="home" class="logo">
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
            <div class="container overflow-hidden" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Home Page</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="row chart-container align-items-center" style="min-height: 447px">
                                        <div class="row book-card align-items-center">
                                            <div class="row align-items-center">
                                                <div class="col-md-7 row mb-5" style="padding-left: 40px;">
                                                    <h1 style="font-size: 3.5rem"><b>BOOK YOUR EVENTS NOW!</b></h1>
                                                    <h6>Book your events now! Secure your date and venue for unforgettable moments.
                                                        Don’t miss out—let’s make it happen!</h6>
                                                    <form action="appointment.php" class="me-3">
                                                        <button class="btn btn-success aptn-btn mt-3" style="width: 400px;">Make an Appointment</button>
                                                    </form>
                                                    <style>
                                                        .aptn-btn {
                                                            background-color: #00A33C !important;
                                                            height: 5rem;
                                                            margin-bottom: -80px;
                                                            font-size: 1.5rem;
                                                            width: 500px;
                                                            border-radius: 10px;
                                                            margin-left: 12px;
                                                        }
                                                    </style>
                                                </div>
                                                <div class="col-md-5 mt-4">
                                                    <img src="../assets/img/event-img.png" alt="events" class="img-fluid"
                                                        style="margin-left: 50px; width: 850px; height: 300px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="myChartLegend"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-primary card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Events this month</div>
                                        <div class="card-tools">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-label-light dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-bs-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Church
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item active" href="#" onclick="updateDropdown('Your Events', this)">Your Events</a>
                                                    <a class="dropdown-item" href="#" onclick="updateDropdown('Public', this)">Public Events</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-category">October</div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2" id="eventList">
                                        <!-- Church events by default -->
                                        <ul>
                                            <li>Worship Services</li>
                                            <li>Bible Study Sessions</li>
                                            <li>Youth and Family Retreats</li>
                                            <li>Community Outreach Programs</li>
                                            <li>Special Holiday Services (Christmas, Easter, etc.)</li>
                                            <li>Workshops and Seminars</li>
                                            <li>Concerts and Music Events</li>
                                            <li>Mission and Volunteer Opportunities</li>
                                            <li>Prayer Meetings</li>
                                            <li>Social Gatherings and Potlucks</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-round justify-content-center" style="height: 105px;">
                                <div class="row card-body align-items-center justify-content-between">
                                    <div class="col-10 mb-3 fs-3 d-flex align-items-center" style="height: 100%;">
                                        <i class="bi bi-calendar3 fs-1 me-4" style="margin-top: 3px;"></i>
                                        <span>View Calendar</span>
                                    </div>
                                    <div class="col-2 h3 d-flex align-items-center justify-content-center" style="height: 100%;">
                                        <i class="bi bi-caret-right-fill"></i>
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

    <?php include("partial/script.php"); ?>
</body>

</html>