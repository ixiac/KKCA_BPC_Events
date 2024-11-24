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
    $user_id = $row["SFID"];

    $active = 'home';
    $currentMonth = date("F");
}

$aptncm = "SELECT COUNT(*) AS appointment_count
        FROM appointment
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE) AND YEAR(start_date) = YEAR(CURRENT_DATE)";
$result = $conn->query($aptncm);

$appointment_count = 0;
if ($result->num_rows > 0) {
    $aptncmc = $result->fetch_assoc();
    $appointment_count = $aptncmc['appointment_count'];
}

$scevcm = "SELECT COUNT(*) AS school_count
        FROM school_events
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE) AND YEAR(start_date) = YEAR(CURRENT_DATE)";
$result = $conn->query($scevcm);

$school_count = 0;
if ($result->num_rows > 0) {
    $scevcmc = $result->fetch_assoc();
    $school_count = $scevcmc['school_count'];
}

$chevcm = "SELECT COUNT(*) AS church_count
        FROM church_events
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE) AND YEAR(start_date) = YEAR(CURRENT_DATE)";
$result = $conn->query($chevcm);

$church_count = 0;
if ($result->num_rows > 0) {
    $chevcmc = $result->fetch_assoc();
    $church_count = $chevcmc['church_count'];
}

$dailyaptn = "SELECT COUNT(*) AS daily_aptn
        FROM appointment
        WHERE DATE(date_created) = CURDATE()";
$result = $conn->query($dailyaptn);

$daily_aptn = 0;
if ($result->num_rows > 0) {
    $show_daily_aptn = $result->fetch_assoc();
    $daily_aptn = $show_daily_aptn['daily_aptn'];
}

$dailyevent = "SELECT COUNT(*) AS daily_event
        FROM appointment
        WHERE DATE(start_date) = CURDATE()";
$result = $conn->query($dailyevent);

$daily_event = 0;
if ($result->num_rows > 0) {
    $show_daily_event = $result->fetch_assoc();
    $daily_event = $show_daily_event['daily_event'];
}

$month_aptn = " SELECT (SELECT COUNT(*) FROM appointment WHERE DATE(start_date) = CURDATE()) AS today_count,
                (SELECT COUNT(*) FROM appointment WHERE MONTH(start_date) = MONTH(CURDATE()) AND YEAR(start_date) = YEAR(CURDATE())) AS total_month_count";
$result = $conn->query($month_aptn);

$percentage_change = 0;
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $aptn_today = $data['today_count'];
    $aptn_month = $data['total_month_count'];

    if ($aptn_month > 0) {
        $percentage_change = ($aptn_today / $aptn_month) * 100;
    }
}

$appointmentsData = [
    'labels' => [],
    'values' => []
];

$daysInMonthQuery = " 
    SELECT DAY(date_field) AS day
    FROM (
        SELECT CURDATE() - INTERVAL (DAY(CURDATE()) - 1) DAY + INTERVAL (d1.n * 10 + d2.n) DAY AS date_field
        FROM (SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 
              UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS d1,
             (SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 
              UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS d2
    ) AS dates
    WHERE MONTH(date_field) = MONTH(CURRENT_DATE) AND YEAR(date_field) = YEAR(CURRENT_DATE)
      AND date_field <= CURDATE()  -- Limit to the current day
    ORDER BY date_field ASC
";

$sparkmonth = "
    SELECT d.day, IFNULL(a.appointment_count, 0) AS appointment_count
    FROM ($daysInMonthQuery) AS d
    LEFT JOIN (
        SELECT COUNT(*) AS appointment_count, DAY(date_created) AS day 
        FROM appointment 
        WHERE MONTH(date_created) = MONTH(CURRENT_DATE) 
          AND YEAR(date_created) = YEAR(CURRENT_DATE) 
        GROUP BY DAY(date_created)
    ) AS a ON d.day = a.day
    ORDER BY d.day ASC
";

$sparkresult = $conn->query($sparkmonth);
while ($spark = $sparkresult->fetch_assoc()) {
    $appointmentsData['labels'][] = $spark['day'];
    $appointmentsData['values'][] = $spark['appointment_count'];
}

$appointmentsDataStart = [
    'labels' => [],
    'values' => []
];

$daysInMonthQueryStart = " 
    SELECT DAY(date_field) AS day
    FROM (
        SELECT CURDATE() - INTERVAL (DAY(CURDATE()) - 1) DAY + INTERVAL (d1.n * 10 + d2.n) DAY AS date_field
        FROM (SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 
              UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS d1,
             (SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 
              UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS d2
    ) AS dates
    WHERE MONTH(date_field) = MONTH(CURRENT_DATE) AND YEAR(date_field) = YEAR(CURRENT_DATE)
    ORDER BY date_field ASC
";

$sparkmonthStart = "
    SELECT d.day, IFNULL(a.appointment_count, 0) AS appointment_count
    FROM ($daysInMonthQueryStart) AS d
    LEFT JOIN (
        SELECT COUNT(*) AS appointment_count, DAY(start_date) AS day 
        FROM appointment 
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE) 
          AND YEAR(start_date) = YEAR(CURRENT_DATE) 
        GROUP BY DAY(start_date)
    ) AS a ON d.day = a.day
    ORDER BY d.day ASC
";

$sparkresultStart = $conn->query($sparkmonthStart);
while ($spark = $sparkresultStart->fetch_assoc()) {
    $appointmentsDataStart['labels'][] = $spark['day'];
    $appointmentsDataStart['values'][] = $spark['appointment_count'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sparkline@3.0.0/dist/jquery.sparkline.min.js"></script>

    <link href="../assets/css/calendar.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>
        <div class="main-panel">
            <div class="main-header">
                <?php include("partial/navbar.php"); ?>
            </div>
            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Staff Page</h3>
                        </div>
                    </div>

                    <!-- Calendar Modal -->
                    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="calendarModalLabel">Appointment Calendar</h5>
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
                                    <h5 class="modal-title" id="calendarModalLabel">School Calendar</h5>
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
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <iconify-icon icon="teenyicons:appointments-solid" style="color: white"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div>
                                                <p class="card-category">Appointments this month:</p>
                                                <h4 class="card-title"><?php echo number_format($appointment_count); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                <iconify-icon icon="teenyicons:school-solid" style="color: white"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">School events this month:</p>
                                                <h4 class="card-title"><?php echo number_format($school_count); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <iconify-icon icon="material-symbols:church" style="color: white"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Church events this month:</p>
                                                <h4 class="card-title"><?php echo number_format($church_count); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 d-flex flex-column">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col card-title">Appointment Statuses</div>
                                        <div class="col card-title text-end"><?php echo $currentMonth; ?></div>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <div id="pie-alert-container"></div>
                                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 45%; width: 45%;">
                                        <canvas id="piechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex flex-column">
                            <div class="card flex-fill">
                                <div class="card-body pb-0">
                                    <div class="fs-2 fw-bold float-end text-primary"><?php echo number_format($percentage_change); ?>%</div>
                                    <h2 class="mb-2"><?php echo number_format($daily_aptn); ?></h2>
                                    <p class="text-muted">New Appointments</p>
                                    <div class="pull-in sparkline-fix ps-2 pe-4">
                                        <div id="aptnchart" style="width: 100%; height: 102px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card flex-fill">
                                <div class="card-body pb-0">
                                    <div class="fs-2 fw-bold float-end text-primary"><?php echo number_format($percentage_change); ?>%</div>
                                    <p class="fs-3 mb-2">Events today: <?php echo number_format($daily_event); ?></p>
                                    <p class="text-muted">Events in <?php echo $currentMonth; ?></p>
                                    <div class="pull-in sparkline-fix ps-2 pe-4">
                                        <div id="cataptnchart" style="width: 100%; height: 102px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
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
                    </div> -->
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

        fetch('modal/staffpiechart.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data || !('pending' in data) || !('completed' in data) || !('cancelled' in data) || !('total' in data)) {
                    console.error('Invalid data format:', data);
                    return;
                }

                const {
                    pending,
                    completed,
                    cancelled,
                    total
                } = data;
                const pendingPercentage = total ? (pending / total) * 100 : 0;
                const cancelledPercentage = total ? (cancelled / total) * 100 : 0;

                const alertContainer = document.getElementById('pie-alert-container');
                alertContainer.innerHTML = '';

                if (pendingPercentage > 20) {
                    alertContainer.innerHTML += `
                <div class="alert text-center" style="border-radius: 10px; background-color: #d33; color: white;">
                    Action Required: More than 20% of appointments left pending!
                </div>
            `;
                }
                if (cancelledPercentage > 20) {
                    alertContainer.innerHTML += `
                <div class="alert text-center" style="border-radius: 10px; background-color: #d33; color: white;">
                    Action Required: More than 20% of appointments are cancelled!
                </div>
            `;
                }

                const canvas = document.getElementById('piechart');
                if (!canvas) {
                    console.error('Canvas element not found');
                    return;
                }
                const ctx = canvas.getContext('2d');

                if (window.statusChart) {
                    window.statusChart.destroy();
                }

                window.statusChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Pending', 'Completed', 'Cancelled'],
                        datasets: [{
                            data: [pending, completed, cancelled],
                            backgroundColor: [
                                'rgba(255, 205, 86, 0.7)',
                                'rgba(0, 163, 60, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 205, 86, 1)',
                                'rgba(0, 163, 60, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 1,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                align: 'center'
                            },
                            tooltip: {
                                enabled: true,
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching status data:', error));

        $(document).ready(function() {
            const data = <?php echo json_encode($appointmentsData); ?>;
            console.log(data);

            if (Array.isArray(data.values) && data.values.length > 0) {
                $('#aptnchart').sparkline(data.values, {
                    type: 'line',
                    width: '100%',
                    height: '107px',
                    lineColor: 'transparent',
                    fillColor: 'rgba(63, 119, 226, 0.2)',
                    lineWidth: 2,
                    spotColor: '#2ecc71',
                    minSpotColor: '#e74c3c',
                    maxSpotColor: '#f39c12',
                    highlightSpotColor: '#3498db',
                    highlightLineColor: '#e74c3c',
                    tooltipChartTitle: 'Appointments',
                    spotRadius: 0
                });
            } else {
                console.error("Invalid data for sparkline", data.values);
            }
        });

        $(document).ready(function() {
            const data = <?php echo json_encode($appointmentsDataStart); ?>;
            console.log(data);

            if (Array.isArray(data.values) && data.values.length > 0) {
                $('#cataptnchart').sparkline(data.values, {
                    type: 'line',
                    width: '100%',
                    height: '107px',
                    lineColor: 'transparent',
                    fillColor: 'rgba(32, 59, 112, 0.4)',
                    lineWidth: 2,
                    spotColor: '#2ecc71',
                    minSpotColor: '#e74c3c',
                    maxSpotColor: '#f39c12',
                    highlightSpotColor: '#3498db',
                    highlightLineColor: '#e74c3c',
                    tooltipChartTitle: 'Appointments',
                    spotRadius: 0
                });
            } else {
                console.error("Invalid data for sparkline", data.values);
            }
        });
    </script>

</body>

</html>