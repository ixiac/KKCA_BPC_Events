<?php
include("../partial/db.php");

$query = "SELECT SUM(exp_cost) AS total_exp_cost, SUM(total_cost) AS total_actual_cost FROM appointment";

$result = $conn->query($query);

if ($result) {
    $data = $result->fetch_assoc();

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Failed to fetch data']);
}
?>