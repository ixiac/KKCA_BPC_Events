<?php
session_start();
include("../partial/db.php");

$year = $_GET['year'] ?? date('Y');
$data = [
    'months' => [],
    'donation' => [],
    'expenses' => [],
    'variance' => [],
    'recommendation' => ''
];

for ($i = 1; $i <= 12; $i++) {
    $data['months'][] = date("F", mktime(0, 0, 0, $i, 1));
    $data['donation'][] = 0;
    $data['expenses'][] = 0;
    $data['variance'][] = 0;
}

$query = "SELECT MONTH(start_date) AS month, SUM(donation) AS donation, SUM(expenses) AS expenses
          FROM church_events
          WHERE YEAR(start_date) = ?
          GROUP BY MONTH(start_date)";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $year);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)$row['month'] - 1;
    $data['donation'][$monthIndex] = (float)$row['donation'];
    $data['expenses'][$monthIndex] = (float)$row['expenses'];
}

$totalDonation = array_sum($data['donation']);
$totalExpenses = array_sum($data['expenses']);
$variance = ($totalDonation > 0) ? (($totalExpenses - $totalDonation) / $totalDonation) * 100 : 0;
$efficiency = ($totalDonation > 0) ? ($totalExpenses / $totalDonation) : 0;

if ($variance > 20) {
    $data['recommendation'] = 'High overspending. Review your budget and reduce unnecessary costs.';
} elseif ($variance > 0 && $variance <= 20) {
    $data['recommendation'] = 'Slight overspending. Monitor expenses closely and seek ways to optimize resources.';
} elseif ($variance < -10) {
    $data['recommendation'] = 'Underutilized budget. Reallocate unused funds to essential programs.';
} else {
    $data['recommendation'] = 'Expenses are balanced. Continue following the current budget strategy.';
}

header('Content-Type: application/json');
echo json_encode($data);