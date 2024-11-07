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

    $active = 'home';
    $currentMonth = date("F");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

    <link href="../assets/css/calendar.css" rel="stylesheet" />

</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>

        <div class="main-panel">

            <div class="main-header" >
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
            <div class="container" style="background-color: #dbdde0 !important; height: 250px !important;">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Home Page</h3>
                        </div>
                    </div>

                    <!-- Calendar Modal -->
                    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="calendarModalLabel">Event Calendar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="scroll overflow-y-scroll p-2">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
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
                                        <div class="card-title fs-2 ms-2">Available Events</div>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                    <div class="card-category fs-3 ms-2"><?php echo $currentMonth?></div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2" id="eventList">
                                        <ul class="fs-4 ms-4">
                                            <li>Wedding</li>
                                            <li>Baptism</li>
                                            <li>Celebrations</li>
                                            <li>Funerals</li>
                                            <li>Community Outreach</li>
                                            <li>Youth Fellowship</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                                <button class="container-fluid btn card card-round justify-content-center" style="height: 100px; border-radius: 10px" data-bs-toggle="modal" data-bs-target="#calendarModal">
                                    <div class="row card-body align-items-center justify-content-between">
                                        <div class="col mb-3 fs-2 d-flex align-items-center" style="height: 100%;">
                                            <i class="bi bi-calendar3 fs-1 me-4" style="margin-top: 3px;"></i>
                                            <span>View Calendar</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: 'modal/calendar.php',
                    method: 'GET',
                    failure: function() {
                        alert('Failed to load events!');
                    },
                    success: function(data) {
                        console.log("Loaded events:", data);
                    }
                },
                eventColor: '#00A33C',
                locale: 'en'
            });

            // Initialize calendar when the modal is shown
            $('#calendarModal').on('shown.bs.modal', function() {
                calendar.render();
            });
        });
    </script>

</body>

</html>