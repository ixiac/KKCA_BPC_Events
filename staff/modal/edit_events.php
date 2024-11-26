<?php
session_start();
include("../partial/db.php");

$SCID = $_POST['SCID'];
$event_name = $_POST['event_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$attendees = $_POST['attendees'];
$budget = $_POST['budget'];
$expenses = $_POST['expenses'];

// Update the database
$sql = "UPDATE school_events 
        SET event_name = ?, start_date = ?, end_date = ?, attendees = ?, budget = ?, expenses = ?
        WHERE SCID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiiis", $event_name, $start_date, $end_date, $attendees, $budget, $expenses, $SCID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
$stmt->close();
?>
