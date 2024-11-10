<?php

session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM customer WHERE CID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["CID"];
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
                        <a href="index.html" class="logo">
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
                            <h3 class="fw-bold mb-3 ms-3">Appointment</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-body ps-5 pe-5">
                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-6 form-group">
                                            <label for="fname">Event Name</label>
                                            <input type="text" class="form-control" id="fname" placeholder="First Name">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="form-group">
                                                <label for="eventForForm">Event For</label>
                                                <select class="form-select" id="eventForForm">
                                                    <option>Church</option>
                                                    <option>Wedding</option>
                                                    <option>Anniversary</option>
                                                    <option>Foundation Day</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row px-3">
                                        <div class="col-md-6 form-group">
                                            <label for="categorySelect">Category</label>
                                            <select class="form-select" id="category-select">
                                                <option>Wedding</option>
                                                <option>Baptism</option>
                                                <option>Celebrations</option>
                                                <option>Funerals</option>
                                                <option>Community Outreach</option>
                                                <option>Youth Fellowship</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="venueSelect">Venue</label>
                                            <select class="form-select" id="venue-select">
                                                <option>Bay City Mall</option>
                                                <option>Malitam University</option>
                                                <option>Malitam Brgy. Hall</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-6 form-group">
                                            <div class="row">
                                                <label for="startDate">Start Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="datetime-local" class="form-control" placeholder="Start Date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="row">
                                                <label for="endDate">End Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="datetime-local" class="form-control" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ps-3 pe-3">
                                        <div class="col-md-6 form-group">
                                            <div class="row">
                                                <label for="startTime">Start Time</label>
                                                <div class="input-group mb-3">
                                                    <input type="time" class="form-control" placeholder="Start Time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <div class="row">
                                                <label for="endTime">End Time</label>
                                                <div class="input-group mb-3">
                                                    <input type="time   " class="form-control" placeholder="End Time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="regFee">Registration Fee</label>
                                        <input type="number" class="form-control" id="registration-fee" placeholder="Registration Fee">
                                        <small id="minFeeText" class="form-text text-muted ps-2">Minimum of P1000</small>
                                    </div>
                                    <div class="row justify-content-center mx-2 mt-3">
                                        <button class="btn btn-primary fs-5" style="width: 300px;">Submit</button>
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