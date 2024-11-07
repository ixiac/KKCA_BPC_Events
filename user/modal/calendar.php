<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

// Fetch events with start and end dates
$sql = "SELECT * FROM appointment";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$events = [];
while ($date = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => $date['event_name'],
        'start' => date('c', strtotime($date['start_date'])), // Convert to ISO 8601 format
        'end' => date('c', strtotime($date['end_date'])), // Convert to ISO 8601 format
        'category' => $date['category']
    ];
}

// Return events as JSON
echo json_encode($events);
?>
