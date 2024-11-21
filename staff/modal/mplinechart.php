<?php
include("../partial/db.php");

$query = "SELECT category, total_cost, start_date FROM appointment";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);

mysqli_close($conn);
?>