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


$apttotal = "SELECT SUM(reg_fee) AS total_amount FROM appointment";
$result = $conn->query($apttotal);

$total_amount = 0;
if ($result->num_rows > 0) {
    $apttotal_sum = $result->fetch_assoc();
    $total_amount = $apttotal_sum['total_amount'];
}

$aptyear = "SELECT SUM(reg_fee) AS total_amount_year FROM appointment WHERE YEAR(start_date) = YEAR(CURRENT_DATE)";
$result = $conn->query($aptyear);

$total_amount_year = 0;
if ($result->num_rows > 0) {
    $aptyear_sum = $result->fetch_assoc();
    $total_amount_year = $aptyear_sum['total_amount_year'];
}

$aptmonth = "SELECT SUM(reg_fee) AS total_amount_month FROM appointment WHERE MONTH(start_date) = MONTH(CURRENT_DATE) AND YEAR(start_date) = YEAR(CURRENT_DATE)";
$result = $conn->query($aptmonth);

$total_amount_month = 0;
if ($result->num_rows > 0) {
    $aptmonth_sum = $result->fetch_assoc();
    $total_amount_month = $aptmonth_sum['total_amount_month'];
}

$apttoday = "SELECT SUM(reg_fee) AS total_amount_today FROM appointment WHERE DATE(start_date) = CURRENT_DATE";
$result = $conn->query($apttoday);

$total_amount_today = 0;
if ($result->num_rows > 0) {
    $apttoday_sum = $result->fetch_assoc();
    $total_amount_today = $apttoday_sum['total_amount_today'];
}

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
                            <h3 class="fw-bold mb-3">Appointment Finance Analysis</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <span class="stamp stamp-md bg-secondary me-3">
                                        <i class="fa fa-dollar-sign"></i>
                                    </span>
                                    <div>
                                        <h5 class="mb-1">
                                            <b><a href="#">₱<?php echo number_format($total_amount); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Total Transactions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <span class="stamp stamp-md bg-success me-3">
                                        <i class="fa fa-shopping-cart"></i>
                                    </span>
                                    <div>
                                        <h5 class="mb-1">
                                            <b><a href="#">₱<?php echo number_format($total_amount_year); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Transactions this year</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <span class="stamp stamp-md bg-danger me-3">
                                        <i class="fa fa-users"></i>
                                    </span>
                                    <div>
                                        <h5 class="mb-1">
                                            <b><a href="#">₱<?php echo number_format($total_amount_month); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Transactions this month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card p-3">
                                <div class="d-flex align-items-center">
                                    <span class="stamp stamp-md bg-warning me-3">
                                        <i class="fa fa-comment-alt"></i>
                                    </span>
                                    <div>
                                        <h5 class="mb-1">
                                            <b><a href="#">₱<?php echo number_format($total_amount_today); ?></a></b>
                                        </h5>
                                        <small class="text-muted">Transactions today</small>
                                    </div>
                                </div>
                            </div>
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
            return fetch(`modal/flinechart.php?year=${year}`)
                .then(response => response.json())
                .catch(error => console.error('Error fetching data:', error));
        };

        const renderChart = async (year) => {
            const data = await fetchData(year);

            const chartData = {
                labels: data.months,
                datasets: [{
                        label: 'Expected Cost',
                        data: data.exp_cost,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.3)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    },
                    {
                        label: 'Total Cost',
                        data: data.total_cost,
                        borderColor: 'rgba(64, 158, 255, 1)',
                        backgroundColor: 'rgba(64, 158, 255, 0.3)',
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
                            text: `Cost Overview for ${year}`
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
                                text: 'Cost (in currency)'
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

        fetch('modal/fbarchart.php')
            .then(response => response.json())
            .then(data => {
                const years = data.years;
                const exp_costs = data.exp_costs;
                const total_costs = data.total_costs;

                const ctx = document.getElementById('fbarchart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [{
                                label: 'Expense Cost',
                                data: exp_costs,
                                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Cost',
                                data: total_costs,
                                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                borderColor: 'rgba(54, 162, 235, 1)',
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

                const analysis = generatePrescriptiveAnalysis(exp_costs, total_costs);

                document.querySelector('.fbar-container').innerHTML = analysis;
            })
            .catch(error => console.error('Error fetching data:', error));

        function generatePrescriptiveAnalysis(exp_costs, total_costs) {
            let analysis = '';

            let expCostTrend = '';
            let totalCostTrend = '';

            if (exp_costs[exp_costs.length - 1] > exp_costs[0]) {
                expCostTrend = 'increasing';
            } else if (exp_costs[exp_costs.length - 1] < exp_costs[0]) {
                expCostTrend = 'decreasing';
            } else {
                expCostTrend = 'stable';
            }

            if (total_costs[total_costs.length - 1] > total_costs[0]) {
                totalCostTrend = 'increasing';
            } else if (total_costs[total_costs.length - 1] < total_costs[0]) {
                totalCostTrend = 'decreasing';
            } else {
                totalCostTrend = 'stable';
            }

            analysis += `<p><strong>Expense Cost Trend:</strong> The expense cost has been ${expCostTrend} over the years.`;
            analysis += `<p><strong>Total Cost Trend:</strong> The total cost has been ${totalCostTrend} over the years.</p>`;

            if (expCostTrend === 'increasing' && totalCostTrend === 'increasing') {
                analysis += `<p><strong>Recommendation:</strong> Both expense and total costs are increasing. It is recommended to analyze the cause of rising expenses and consider optimizing processes to reduce costs in the future.</p>`;
            } else if (expCostTrend === 'decreasing' && totalCostTrend === 'decreasing') {
                analysis += `<p><strong>Recommendation:</strong> Both expense and total costs are decreasing, which is a positive trend. Continue monitoring the costs to ensure they stay under control.</p>`;
            } else if (expCostTrend === 'stable' && totalCostTrend === 'increasing') {
                analysis += `<p><strong>Recommendation:</strong> Total costs are rising while expenses remain stable. It may be beneficial to explore factors contributing to the rise in total costs, such as overheads or external factors.</p>`;
            } else if (expCostTrend === 'increasing' && totalCostTrend === 'stable') {
                analysis += `<p><strong>Recommendation:</strong> If expenses are rising while total costs remain stable, it might indicate inefficiencies in cost allocation or areas where savings can be made. A deeper review of cost distribution is advised.</p>`;
            }

            return analysis;
        }
    </script>

</body>

</html>