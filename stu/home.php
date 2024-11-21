<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM student WHERE SID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["SID"];

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
            <div class="main-header">
                <div class="main-header-logo">
                    <?php include("partial/logo-header.php"); ?>
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>
            <div class="container" style="background-color: #dbdde0 !important;">
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

                    <!-- School Calendar Modal -->
                    <div class="modal fade" id="sc_calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="calendarModalLabel">Event Calendar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="scroll overflow-y-scroll p-2">
                                        <div id="sc_calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="background-overlay2"></div>
                                <style>
                                    .background-overlay2 {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background-image: url('../assets/img/libag.png');
                                        background-position: right center;
                                        background-repeat: no-repeat;
                                        /* opacity: 0.1; */
                                        border-radius: 10px;
                                    }
                                </style>

                                <div class="card-body">
                                    <div class="row chart-container align-items-center" style="height: 447px">
                                        <div class="row book-card align-items-center">
                                            <div class="row align-items-center">
                                                <div class="col-md-7 row mb-5" style="padding-left: 40px;">
                                                    <h1 class="fs-1"><b>BOOK YOUR EVENTS NOW!</b></h1>
                                                    <h6>Book your events now! Secure your date and venue for unforgettable moments.
                                                        Don’t miss out—let’s make it happen!</h6>
                                                    <form action="appointment.php">
                                                        <button class="btn aptn-btn mt-3">Make an Appointment</button>
                                                    </form>
                                                    <style>
                                                        .aptn-btn {
                                                            background-color: #00A33C !important;
                                                            color: white;
                                                            height: 5rem;
                                                            margin-bottom: -80px;
                                                            font-size: 1.5rem;
                                                            width: 100%;
                                                            border-radius: 10px;
                                                        }
                                                    </style>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class="card-category fs-3 ms-2"><?php echo $currentMonth ?></div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="container-fluid btn card card-round justify-content-center" style="height: 100px; border-radius: 10px" data-bs-toggle="modal" data-bs-target="#calendarModal">
                                        <div class="row card-body align-items-center justify-content-between">
                                            <div class="col mb-3 fs-2 d-flex align-items-center" style="height: 100%;">
                                                <i class="bi bi-calendar3 fs-1 me-4" style="margin-top: 3px;"></i>
                                                <span>Public</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="container-fluid btn card card-round justify-content-center" style="height: 100px; border-radius: 10px" data-bs-toggle="modal" data-bs-target="#sc_calendarModal">
                                        <div class="row card-body align-items-center justify-content-between">
                                            <div class="col mb-3 fs-2 d-flex align-items-center" style="height: 100%;">
                                                <i class="bi bi-calendar3 fs-1 me-4" style="margin-top: 3px;"></i>
                                                <span>School</span>
                                            </div>
                                        </div>
                                    </button>
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

            $('#calendarModal').on('shown.bs.modal', function() {
                calendar.render();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('sc_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: 'modal/sc_calendar.php',
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

            $('#sc_calendarModal').on('shown.bs.modal', function() {
                calendar.render();
            });
        });
    </script>

</body>

</html>