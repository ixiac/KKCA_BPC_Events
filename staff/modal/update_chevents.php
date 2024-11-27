<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

$sql = "UPDATE school_events SET event_name = ?, start_date = ?, end_date = ? WHERE CHID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssi', $title, $start, $end, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Event updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update event.']);
}

$stmt->close();
$conn->close();
?>