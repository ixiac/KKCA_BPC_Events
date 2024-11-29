<?php
session_start();
include("../partial/db.php");

$query = "SELECT YEAR(start_date) AS year, 
                 SUM(expenses) AS total_expenses, 
                 SUM(budget) AS total_budget
          FROM school_events
          GROUP BY YEAR(start_date)
          ORDER BY year";
$result = mysqli_query($conn, $query);

$years = [];
$expenses = [];
$budget = [];
$total_expenses = 0;
$total_budget = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['year'];
    $expenses[] = $row['total_expenses'];
    $budget[] = $row['total_budget'];
    $total_expenses += $row['total_expenses'];
    $total_budget += $row['total_budget'];
}

$data = [
    'years' => $years,
    'expenses' => $expenses,
    'budget' => $budget,
    'total_expenses' => $total_expenses,
    'total_budget' => $total_budget
];

echo json_encode($data);
?>