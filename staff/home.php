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
                (SELECT COUNT(*) FROM appointment WHERE MONTH(date_created) = MONTH(CURDATE()) AND YEAR(date_created) = YEAR(CURDATE())) AS total_month_count";
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

                    <!-- Appointment Calendar Modal -->
                    <div class="modal fade overflow-hidden" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title fs-4" id="calendarModalLabel">Appointment Calendar</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 84vh">
                                    <div class="scroll overflow-y-scroll p-2">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Calendar Modal -->
                    <div class="modal fade overflow-hidden" id="sc_calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title fs-4" id="calendarModalLabel">School Calendar</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="scroll overflow-y-scroll p-2" style="max-height: 84vh">
                                        <div id="sc_calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Church Calendar Modal -->
                    <div class="modal fade overflow-hidden" id="ch_calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title fs-4" id="calendarModalLabel">Church Calendar</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 84vh">
                                    <div class="scroll overflow-y-scroll p-2">
                                        <div id="ch_calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pb-5">
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

                    <div class="row pb-5">
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
                            <div class="card flex-fill" style="background-color: #203b70">
                                <div class="card-body pb-0">
                                    <div class="fs-2 fw-bold float-end" style="color: white;"><?php echo number_format($percentage_change); ?>%</div>
                                    <p class="mb-2 fs-3" style="color: white"><?php echo number_format($daily_aptn); ?></p>
                                    <p style="color: white">New Appointments</p>
                                    <div class="pull-in sparkline-fix pb-2">
                                        <div id="aptnchart" style="width: 100%; height: 102px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card flex-fill">
                                <div class="card-body pb-0">
                                    <div class="fs-2 fw-bold float-end text-primary"><?php echo number_format($percentage_change); ?>%</div>
                                    <p class="fs-3 mb-2">Events today: <?php echo number_format($daily_event); ?></p>
                                    <p class="text-muted">Events in <?php echo $currentMonth; ?></p>
                                    <div class="pull-in sparkline-fix pb-2">
                                        <div id="cataptnchart" style="width: 100%; height: 102px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">School Events of 2024</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="linechart"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a class="float-end" role="button" data-bs-toggle="modal" data-bs-target="#sc_calendarModal" style="color: #203b70">See Calendar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Church Events of 2024</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="chlinechart"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a class="float-end" role="button" data-bs-toggle="modal" data-bs-target="#ch_calendarModal"  style="color: #203b70">See Calendar</a>
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

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('ch_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: 'modal/ch_calendar.php',
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

            $('#ch_calendarModal').on('shown.bs.modal', function() {
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
                    lineColor: 'rgba(219, 221, 224, 1)',
                    fillColor: 'rgba(53, 99, 188, 0.6)',
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
                    fillColor: 'rgba(53, 99, 188, 0.6)',
                    lineWidth: 2,
                    spotColor: '#2ecc71',
                    minSpotColor: '#e74c3c',
                    maxSpotColor: '#f39c12',
                    highlightSpotColor: '#3498db',
                    highlightLineColor: '#e74c3c',
                    tooltipChartTitle: 'Events',
                    spotRadius: 0,
                });
            } else {
                console.error("Invalid data for sparkline", data.values);
            }
        });

        fetch('modal/linechart.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(event => event.month);
                const totalEvents = data.map(event => event.total_events);

                const ctx = document.getElementById('linechart').getContext('2d');

                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Events Added',
                            data: totalEvents,
                            borderColor: 'rgba(32, 59, 112, 0.8)',
                            backgroundColor: 'rgba(63, 119, 226, 0.2)',
                            fill: true,
                            borderWidth: 2,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Months',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Events',
                                },
                                ticks: {
                                    stepSize: 1
                                },
                            },
                        },
                    },
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        fetch('modal/chlinechart.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(event => event.month);
                const totalEvents = data.map(event => event.total_events);

                const ctx = document.getElementById('chlinechart').getContext('2d');

                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Events Added',
                            data: totalEvents,
                            borderColor: 'rgba(32, 59, 112, 0.8)',
                            backgroundColor: 'rgba(63, 119, 226, 0.2)',
                            fill: true,
                            borderWidth: 2,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Months',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Events',
                                },
                                ticks: {
                                    stepSize: 1
                                },
                            },
                        },
                    },
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

</body>

</html>