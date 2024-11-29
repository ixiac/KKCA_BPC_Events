<?php
session_start();
include("../partial/db.php");

$query = "SELECT YEAR(start_date) AS year, 
                 SUM(exp_cost) AS total_exp_cost, 
                 SUM(total_cost) AS total_total_cost
          FROM appointment
          GROUP BY YEAR(start_date)
          ORDER BY year";
$result = mysqli_query($conn, $query);

$years = [];
$exp_costs = [];
$total_costs = [];

while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['year'];
    $exp_costs[] = $row['total_exp_cost'];
    $total_costs[] = $row['total_total_cost'];
}

$data = [
    'years' => $years,
    'exp_costs' => $exp_costs,
    'total_costs' => $total_costs
];

echo json_encode($data);
?>