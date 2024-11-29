<?php
session_start();
include("../partial/db.php");

$year = $_GET['year'] ?? date('Y');
$data = [
    'months' => [],
    'budget' => [],
    'expenses' => [],
    'variance' => [],
    'recommendation' => ''
];

for ($i = 1; $i <= 12; $i++) {
    $data['months'][] = date("F", mktime(0, 0, 0, $i, 1));
    $data['budget'][] = 0;
    $data['expenses'][] = 0;
    $data['variance'][] = 0;
}

$query = "SELECT MONTH(start_date) AS month, SUM(budget) AS budget, SUM(expenses) AS expenses
          FROM school_events
          WHERE YEAR(start_date) = ?
          GROUP BY MONTH(start_date)";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $year);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)$row['month'] - 1;
    $data['budget'][$monthIndex] = (float)$row['budget'];
    $data['expenses'][$monthIndex] = (float)$row['expenses'];
}

$totalBudget = array_sum($data['budget']);
$totalExpenses = array_sum($data['expenses']);
$budgetEfficiency = ($totalBudget > 0) ? ($totalExpenses / $totalBudget) * 100 : 0;

if ($budgetEfficiency > 120) {
    $data['recommendation'] = 'High overspending. Review your budget and reduce unnecessary costs.';
} elseif ($budgetEfficiency > 100 && $budgetEfficiency <= 120) {
    $data['recommendation'] = 'Slight overspending. Monitor expenses closely and seek ways to optimize resources.';
} elseif ($budgetEfficiency < 80) {
    $data['recommendation'] = 'Underutilized budget. Reallocate unused funds to essential programs.';
} else {
    $data['recommendation'] = 'Expenses are balanced. Continue following the current budget strategy.';
}

header('Content-Type: application/json');
echo json_encode($data);
?>