<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM admin WHERE AID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["AID"];

    $active = 'home';
    $currentMonth = date("F");
}

$aptnct = "SELECT COUNT(*) AS appointment_count FROM appointment";
$result = $conn->query($aptnct);

$appointment_tcount = 0;
if ($result->num_rows > 0) {
    $aptnctc = $result->fetch_assoc();
    $appointment_tcount = $aptnctc['appointment_count'];
}

$aptncm = "SELECT COUNT(*) AS appointment_mcount
        FROM appointment
        WHERE MONTH(date_created) = MONTH(CURRENT_DATE) 
          AND YEAR(date_created) = YEAR(CURRENT_DATE)";
$result = $conn->query($aptncm);

$appointment_mcount = 0;
if ($result->num_rows > 0) {
    $aptncmc = $result->fetch_assoc();
    $appointment_mcount = $aptncmc['appointment_mcount'];
}

$aptncy = "SELECT COUNT(*) AS appointment_ycount
        FROM appointment
        WHERE YEAR(date_created) = YEAR(CURRENT_DATE)";
$result = $conn->query($aptncy);

$appointment_ycount = 0;
if ($result->num_rows > 0) {
    $aptncyc = $result->fetch_assoc();
    $appointment_ycount = $aptncyc['appointment_ycount'];
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

$active = "analytics";
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
                            <h3 class="fw-bold mb-3">Appointment Analytics</h3>
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

                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1 ps-2">
                                            <b><a href="#"><?php echo number_format($appointment_tcount); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Total Appointments</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1 ps-2">
                                            <b><a href="#"><?php echo number_format($appointment_ycount); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Appointments this year</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1 ps-2">
                                            <b><a href="#"><?php echo number_format($appointment_mcount); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Appointments this month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1 ps-2">
                                            <b><a href="#"><?php echo number_format($daily_aptn); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Appointments today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pb-3">
                        <select id="yearFilter" class="form-select form-select-sm w-auto" style="background-color: #dbdde0; border: 1px solid gray;">
                        </select>
                    </div>
                    <div class="row pb-5">
                        <div class="col-md-5 d-flex flex-column">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col card-title">Appointment Statuses</div>
                                        <div class="col card-title text-end"></div>
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

                        <div class="col-md-7 d-flex flex-column">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col card-title">Appointment Categories</div>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <div id="cat-alert-container"></div>
                                    <div class="chart-container d-flex justify-content-center align-items-center w-100">
                                        <canvas id="cbarchart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Yearly Trends</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="linechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Trend Over time Analysis</div>
                                </div>
                                <div class="card-body">
                                    <div class="tota-container"></div>
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
        const loadYears = () => {
            const currentYear = new Date().getFullYear();
            const yearFilter = document.getElementById('yearFilter');

            for (let i = currentYear; i >= currentYear - 5; i--) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                yearFilter.appendChild(option);
            }

            yearFilter.value = currentYear;
        };

        const fetchAppointmentStatusData = (year) => {
            return fetch(`modal/piechart.php?year=${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error fetching status data:', error);
                    return {};
                });
        };

        const renderAppointmentStatusPieChart = async (year) => {
            const data = await fetchAppointmentStatusData(year);

            if (!data || typeof data !== 'object' || !data.hasOwnProperty('pending') || !data.hasOwnProperty('approved') || !data.hasOwnProperty('completed') || !data.hasOwnProperty('cancelled') || !data.hasOwnProperty('total')) {
                console.error('Invalid data format:', data);
                return;
            }

            const {
                pending,
                approved,
                completed,
                cancelled,
                total
            } = data;

            const pendingPercentage = total ? (pending / total) * 100 : 0;
            const approvedPercentage = total ? (approved / total) * 100 : 0;
            const cancelledPercentage = total ? (cancelled / total) * 100 : 0;

            const alertContainer = document.getElementById('pie-alert-container');
            alertContainer.innerHTML = '';

            if (pendingPercentage > 20) {
                alertContainer.innerHTML += `
                <div class="alert alert-danger text-center">
                    Action Required: More than 20% of appointments are pending! 
                    Too much pending requests can lead to delays in scheduling, 
                    frustration among customers, and potential loss of trust in the appointment system. 
                </div>
            `;
            }

            const ctx = document.getElementById('piechart').getContext('2d');
            if (window.appointmentPieChart) {
                window.appointmentPieChart.destroy();
            }

            window.appointmentPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Approved', 'Completed', 'Cancelled'],
                    datasets: [{
                        label: 'Appointment Status',
                        data: [pending, approved, completed, cancelled],
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 159, 64, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        };

        const fetchCategoryData = (year) => {
            return fetch(`modal/catbarchart.php?year=${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                });
        };

        const renderCategoryChart = async (year) => {
            const data = await fetchCategoryData(year);

            if (!data || !Array.isArray(data.categories) || !Array.isArray(data.counts)) {
                console.error('Invalid data format:', data);
                return;
            }

            const {
                categories,
                counts
            } = data;

            const maxCount = Math.max(...counts);
            const minCount = Math.min(...counts);
            if (maxCount === 0 && minCount === 0) {
                console.log('No data to display for categories.');
                return;
            }

            const topCategoryIndex = counts.indexOf(maxCount);
            const lowestCategoryIndex = counts.indexOf(minCount);
            const topCategory = categories[topCategoryIndex];
            const lowestCategory = categories[lowestCategoryIndex];

            const recommendationContainer = document.getElementById('cat-alert-container');
            recommendationContainer.innerHTML = '';

            if (maxCount > 0) {
                recommendationContainer.innerHTML += `
        <div class="alert alert-success text-center">
            <strong>Top Category: ${topCategory}</strong> - Consider adding more time slots or resources.
        </div>
    `;
            }

            if (minCount > 0) {
                recommendationContainer.innerHTML += `
        <div class="alert alert-danger text-center">
            <strong>Lowest Category: ${lowestCategory}</strong> - Consider encouraging participation through announcements.
        </div>
    `;
            }

            const canvas = document.getElementById('cbarchart');
            const ctx = canvas.getContext('2d');

            if (window.categoryChart) {
                window.categoryChart.destroy();
            }

            window.categoryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Number of Appointments',
                        data: counts,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(201, 203, 207, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(201, 203, 207, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        };

        const fetchEventData = (year) => {
            return fetch(`modal/linechart.php?year=${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                });
        };

        const renderEventChart = async (year) => {
            const data = await fetchEventData(year);

            if (!data || !Array.isArray(data) || data.length === 0) {
                console.error('Data format issue or no data returned:', data);
                return;
            }

            const labels = data.map(event => event.month || 'Unknown');
            const totalEvents = data.map(event => parseInt(event.total_events) || 0);

            if (totalEvents.every(event => event === 0)) {
                console.log('No events added in any month.');

                const totaContainer = document.querySelector('.tota-container');
                totaContainer.innerHTML = '<p>No events added in this year.</p>';

                const chartCanvas = document.getElementById('linechart');
                chartCanvas.innerHTML = '';
                chartCanvas.width = chartCanvas.width;

                return;
            }

            let maxIndex = totalEvents.indexOf(Math.max(...totalEvents));
            let minIndex = totalEvents.indexOf(Math.min(...totalEvents));

            const highestMonth = labels[maxIndex];
            const lowestMonth = labels[minIndex];
            const highestValue = totalEvents[maxIndex];
            const lowestValue = totalEvents[minIndex];

            const totaContainer = document.querySelector('.tota-container');
            totaContainer.innerHTML = `
        <p><strong>Month with the highest events:</strong> ${highestMonth} (${highestValue} events)</p>
        <p><strong>Month with the lowest events:</strong> ${lowestMonth} (${lowestValue} events)</p>
        <p><strong>Recommendations:</strong></p>
        <ul>
            <li><strong>For ${highestMonth}:</strong> Maintain the strategies that contributed to its success.</li>
            <li><strong>For ${lowestMonth}:</strong> Investigate the reasons for low engagement.</li>
        </ul>
    `;

            const ctx = document.getElementById('linechart').getContext('2d');

            if (window.lineChart) {
                window.lineChart.destroy();
            }

            window.lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Events',
                        data: totalEvents,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        };

        document.getElementById('yearFilter').addEventListener('change', (event) => {
            const selectedYear = event.target.value;
            renderAppointmentStatusPieChart(selectedYear);
            renderCategoryChart(selectedYear);
            renderEventChart(selectedYear);
        });

        loadYears();
        const currentYear = new Date().getFullYear();
        renderAppointmentStatusPieChart(currentYear);
        renderCategoryChart(currentYear);
        renderEventChart(currentYear);
    </script>

</body>

</html>