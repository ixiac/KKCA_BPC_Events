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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

    <link href="../assets/css/calendar.css" rel="stylesheet" />

    <?php include("partial/script.php"); ?>
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

                    <!-- Objective 2 -->
                    <div class="row">
                        <!-- Line Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Trend over time analysis</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="linechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Line Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Trend over time analysis by category</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="mplinechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Objective 3 -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Bar Chart</div>
                                </div>
                                <div class="card-body">
                                    <div id="bar-alert-container"></div>
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="barchart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Pie Chart</div>
                                </div>
                                <div class="card-body" style="width: 60%">
                                    <div id="pie-alert-container"></div>
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="piechart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Donation Chart</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div id="total_donations" class="col-md-3"></div>
                                        <div id="total_budget" class="col-md-3"></div>
                                    </div>
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="donationchart"></canvas>
                                        <div id="recommendation"></div>
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

        fetch('modal/mplinechart.php')
            .then(response => response.json())
            .then(data => {
                const categories = ['Wedding', 'Baptism', 'Celebrations', 'Funerals', 'Community Outreach', 'Youth Fellowship'];
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                const monthlyData = {
                    'Wedding': [],
                    'Baptism': [],
                    'Celebrations': [],
                    'Funerals': [],
                    'Community Outreach': [],
                    'Youth Fellowship': []
                };

                categories.forEach(category => {
                    months.forEach(month => {
                        monthlyData[category].push(0);
                    });
                });

                data.forEach(event => {
                    const eventMonth = new Date(event.start_date).getMonth();
                    const category = event.category;
                    const totalCost = event.total_cost;

                    if (monthlyData[category]) {
                        monthlyData[category][eventMonth] += totalCost;
                    }
                });

                const datasets = categories.map(category => ({
                    label: category,
                    data: monthlyData[category],
                    borderColor: getCategoryColor(category),
                    fill: true,
                    backgroundColor: getCategoryColor(category, 0.2),
                    tension: 0.4,
                    borderWidth: 2,
                }));

                const ctx = document.getElementById('mplinechart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Event Costs by Category'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }

                });

            })
            .catch(error => console.error('Error fetching data:', error));

        function getCategoryColor(category, alpha = 1) {
            switch (category) {
                case 'Wedding':
                    return `rgba(255, 99, 132, ${alpha})`;
                case 'Baptism':
                    return `rgba(54, 162, 235, ${alpha})`;
                case 'Celebrations':
                    return `rgba(255, 206, 86, ${alpha})`;
                case 'Funerals':
                    return `rgba(75, 192, 192, ${alpha})`;
                case 'Community Outreach':
                    return `rgba(153, 102, 255, ${alpha})`;
                case 'Youth Fellowship':
                    return `rgba(255, 159, 64, ${alpha})`;
                default:
                    return `rgba(0, 0, 0, ${alpha})`;
            }
        }

        fetch('modal/barchart.php')
            .then(response => response.json())
            .then(data => {
                const expCost = data.total_exp_cost;
                const totalCost = data.total_actual_cost;

                const threshold = 0.10;
                const difference = Math.abs(expCost - totalCost) / expCost;

                if (difference <= threshold) {
                    document.getElementById('bar-alert-container').innerHTML = `
                        <div class="alert text-center" style="border-radius: 10px; background-color: #d33; color: white;">
                            Warning: The total cost is close to the expected cost!
                        </div>
                    `;
                }

                const ctx = document.getElementById('barchart').getContext('2d');
                const costChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Cost Breakdown'],
                        datasets: [{
                                label: 'Expected Cost',
                                data: [expCost],
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Cost',
                                data: [totalCost],
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        fetch('modal/piechart.php')
            .then(response => response.json())
            .then(data => {
                const {
                    pending,
                    completed,
                    cancelled,
                    total
                } = data;

                const pendingPercentage = (pending / total) * 100;
                const cancelledPercentage = (cancelled / total) * 100;

                const alertContainer = document.getElementById('pie-alert-container');
                alertContainer.innerHTML = '';

                if (pendingPercentage > 20) {
                    alertContainer.innerHTML += `
                <div class="alert text-center" style="border-radius: 10px; background-color: #d33; color: white;">
                    Action Required: More than 20% of appointments are pending!
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

                const ctx = document.getElementById('piechart').getContext('2d');
                const statusChart = new Chart(ctx, {
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
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                enabled: true,
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching status data:', error));

        fetch('modal/donation.php')
            .then(response => response.json())
            .then(data => {
                const months = data.monthly_data.map(event => event.month);
                const donations = data.monthly_data.map(event => event.total_donations);
                const budgets = data.monthly_data.map(event => event.total_budget);

                const ctx = document.getElementById('donationchart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                                label: 'Monthly Donations',
                                data: donations,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: true,
                                borderWidth: 2,
                                tension: 0.4
                            },
                            {
                                label: 'Monthly Budget',
                                data: budgets,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                fill: true,
                                borderWidth: 2,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (in currency)'
                                }
                            }
                        }
                    }
                });

                document.getElementById('total_donations').innerHTML = `Total Donations: ${data.total_donations}`;
                document.getElementById('total_budget').innerHTML = `Total Budget: ${data.total_budget}`;
                document.getElementById('recommendation').innerHTML = `<h3>Overall Recommendation:</h3><p>${data.recommendation}</p>`;
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

</body>

</html>