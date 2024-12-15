<?php
session_start();
include("../partial/db.php");

$query = "SELECT YEAR(start_date) AS year, 
                SUM(donation) AS total_donation, 
                SUM(budget) AS total_budget,
                SUM(expenses) AS total_expenses
          FROM church_events
          GROUP BY YEAR(start_date)
          ORDER BY year";
$result = mysqli_query($conn, $query);

$years = [];
$donations = [];
$expenses = [];
$total_donations = 0;
$total_expenses = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['year'];
    $donations[] = $row['total_donation'] + $row['total_budget'];
    $expenses[] = $row['total_expenses'];
    $total_donations += $row['total_donation'];
    $total_expenses += $row['total_expenses'];
}

$data = [
    'years' => $years,
    'donations' => $donations,
    'expenses' => $expenses,
    'total_donations' => $total_donations,
    'total_expenses' => $total_expenses
];

echo json_encode($data);
?>