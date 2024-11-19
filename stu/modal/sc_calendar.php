<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

$sql = "SELECT * FROM school_events";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$chevents = [];
while ($chdate = mysqli_fetch_assoc($result)) {
    $chevents[] = [
        'title' => $chdate['event_name'],
        'start' => date('c', strtotime($chdate['start_date'])),
        'end' => date('c', strtotime($chdate['end_date'])),
    ];
}

echo json_encode($chevents);
?>
