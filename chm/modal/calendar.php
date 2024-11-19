<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

$sql = "SELECT * FROM appointment";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$events = [];
while ($date = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => $date['event_name'],
        'start' => date('c', strtotime($date['start_date'])),
        'end' => date('c', strtotime($date['end_date'])),
        'category' => $date['category']
    ];
}

echo json_encode($events);
?>
