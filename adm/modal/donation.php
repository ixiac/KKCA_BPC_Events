<?php
include("../partial/db.php");

$sql = "SELECT YEAR(start_date) AS year, MONTH(start_date) AS month, SUM(donation) AS total_donations, SUM(budget) AS total_budget FROM church_events
GROUP BY YEAR(start_date), MONTH(start_date) ORDER BY YEAR(start_date), MONTH(start_date);";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $monthlyData = [];

    while ($row = $result->fetch_assoc()) {
        $formatted_month = date("M", mktime(0, 0, 0, $row['month'], 10));

        $monthlyData[] = [
            'month' => $formatted_month,
            'total_donations' => (int)$row['total_donations'],
            'total_budget' => (int)$row['total_budget'],
        ];
    }

    $total_donations = array_sum(array_column($monthlyData, 'total_donations'));
    $total_budget = array_sum(array_column($monthlyData, 'total_budget'));

    if ($total_donations < $total_budget) {
        $recommendation = "Increase fundraising efforts to meet the target budget for the year.";
    } elseif ($total_donations === $total_budget) {
        $recommendation = "Donations have perfectly matched the target budget for the year!";
    } else {
        $recommendation = "Excellent! Donations have exceeded the planned budget for the year.";
    }

    echo json_encode([
        'monthly_data' => $monthlyData,
        'total_donations' => $total_donations,
        'total_budget' => $total_budget,
        'recommendation' => $recommendation
    ]);
} else {
    echo json_encode([]);
}
