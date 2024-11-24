<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

date_default_timezone_set('Asia/Manila');

$sql = "SELECT * FROM school_events";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$chevents = [];
while ($chdate = mysqli_fetch_assoc($result)) {
    $start_date = new DateTime($chdate['start_date'], new DateTimeZone('UTC'));
    $start_date->setTimezone(new DateTimeZone('Asia/Manila'));
    $start_date = $start_date->format('Y-m-d H:i:s');

    $end_date = new DateTime($chdate['end_date'], new DateTimeZone('UTC'));
    $end_date->setTimezone(new DateTimeZone('Asia/Manila'));
    $end_date = $end_date->format('Y-m-d H:i:s');

    $chevents[] = [
        'id' => $chdate['SCID'],
        'title' => $chdate['event_name'],
        'start' => $start_date,
        'end' => $end_date,
    ];
}

echo json_encode($chevents);
?>