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

$aptncm = "SELECT COUNT(*) AS appointment_mcount
        FROM appointment
        WHERE MONTH(date_created) = MONTH(CURRENT_DATE) AND YEAR(date_created) = YEAR(CURRENT_DATE)";
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
                            <h3 class="fw-bold mb-3">Church Donation Analysis</h3>
                        </div>
                    </div>


                    <div class="pb-2">
                        <select id="yearSelect">
                            <script>
                                const currentYear = new Date().getFullYear();
                                for (let year = currentYear; year >= currentYear - 5; year--) {
                                    document.write(`<option value="${year}">${year}</option>`);
                                }
                            </script>
                        </select>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <div id="recommendation" class="fs-6" style="font-weight: bold; color: #555;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Yearly Expenses</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 100%; width: 100%;">
                                        <canvas id="fbarchart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Yearly Analysis</div>
                                </div>
                                <div class="card-body">
                                    <div class="fbar-container"></div>
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
        const ctx = document.getElementById('lineChart').getContext('2d');
        let lineChart;

        const fetchData = (year) => {
            return fetch(`modal/dlinechart.php?year=${year}`)
                .then(response => response.json())
                .catch(error => console.error('Error fetching data:', error));
        };

        const renderChart = async (year) => {
            const data = await fetchData(year);

            const chartData = {
                labels: data.months,
                datasets: [{
                        label: 'Donations',
                        data: data.donation,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    },
                    {
                        label: 'Expenses',
                        data: data.expenses,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }
                ]
            };

            if (lineChart) lineChart.destroy();

            lineChart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `Financial Overview for ${year}`
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Months'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Amount (in currency)'
                            }
                        }
                    }
                }
            });

            const recommendationDiv = document.getElementById('recommendation');
            recommendationDiv.textContent = `Recommendation: ${data.recommendation}`;
        };

        document.getElementById('yearSelect').addEventListener('change', (e) => {
            renderChart(e.target.value);
        });

        renderChart(currentYear);

        fetch('modal/dbarchart.php')
            .then(response => response.json())
            .then(data => {
                const years = data.years;
                const donations = data.donations;
                const expenses = data.expenses;
                const total_donations = data.total_donations;
                const total_expenses = data.total_expenses;

                const ctx = document.getElementById('fbarchart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [{
                                label: 'Donations',
                                data: donations,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Expenses',
                                data: expenses,
                                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                                borderColor: 'rgba(255, 159, 64, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                const analysis = generatePrescriptiveAnalysis(donations, expenses, total_donations, total_expenses);

                document.querySelector('.fbar-container').innerHTML = analysis;
            })
            .catch(error => console.error('Error fetching data:', error));

        function generatePrescriptiveAnalysis(donations, expenses, total_donations, total_expenses) {
            let analysis = '';
            let donationTrend = '';
            let expenseTrend = '';

            if (donations[donations.length - 1] > donations[0]) {
                donationTrend = 'increasing';
            } else if (donations[donations.length - 1] < donations[0]) {
                donationTrend = 'decreasing';
            } else {
                donationTrend = 'stable';
            }

            if (expenses[expenses.length - 1] > expenses[0]) {
                expenseTrend = 'increasing';
            } else if (expenses[expenses.length - 1] < expenses[0]) {
                expenseTrend = 'decreasing';
            } else {
                expenseTrend = 'stable';
            }

            const donationPercentage = ((total_donations / total_expenses) * 100).toFixed(2);

            analysis += `<p><strong>Total Donations:</strong> ₱${total_donations.toFixed(2)}`;
            analysis += `<p><strong>Total Expenses:</strong> ₱${total_expenses.toFixed(2)}</p>`;
            analysis += `<p><strong>Donation to Expenses Ratio:</strong> Donations cover ${donationPercentage}% of the total expenses.</p>`;

            return analysis;
        }
    </script>

</body>

</html>