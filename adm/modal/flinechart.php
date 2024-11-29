<?php
session_start();
include("../partial/db.php");

$year = $_GET['year'] ?? date('Y');
$data = [
    'months' => [],
    'exp_cost' => [],
    'total_cost' => [],
    'profit' => []
];

for ($i = 1; $i <= 12; $i++) {
    $data['months'][] = date("F", mktime(0, 0, 0, $i, 1));
    $data['exp_cost'][] = 0;
    $data['total_cost'][] = 0;
    $data['profit'][] = 0;
}

$query = "SELECT MONTH(start_date) AS month, SUM(exp_cost) AS exp_cost, SUM(total_cost) AS total_cost
          FROM appointment
          WHERE YEAR(start_date) = ?
          GROUP BY MONTH(start_date)";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $year);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)$row['month'] - 1;
    $data['exp_cost'][$monthIndex] = (float)$row['exp_cost'];
    $data['total_cost'][$monthIndex] = (float)$row['total_cost'];
    $data['profit'][$monthIndex] = (float)$row['exp_cost'] - (float)$row['total_cost'];
}

$totalExpCost = array_sum($data['exp_cost']);
$totalActualCost = array_sum($data['total_cost']);
$totalProfit = $totalExpCost - $totalActualCost;
$profitMargin = ($totalExpCost > 0) ? ($totalProfit / $totalExpCost) * 100 : 0;

if ($profitMargin > 20) {
    $data['recommendation'] = 'Excellent profitability. Consider reinvesting profits into strategic growth areas.';
} elseif ($profitMargin > 10 && $profitMargin <= 20) {
    $data['recommendation'] = 'Healthy profitability. Maintain current strategies and explore further efficiency improvements.';
} elseif ($profitMargin > 0 && $profitMargin <= 10) {
    $data['recommendation'] = 'Low profitability. Evaluate cost structures and identify potential savings or revenue growth opportunities.';
} elseif ($profitMargin == 0) {
    $data['recommendation'] = 'Break-even point reached. Explore ways to increase revenue or reduce costs to achieve profitability.';
} else {
    $data['recommendation'] = 'Negative profitability. Immediate action is needed to reduce costs or enhance revenue streams.';
}

header('Content-Type: application/json');
echo json_encode($data);
