<?php
session_start();
include("../partial/db.php");

$CHID = $_POST['CHID'];
$event_name = $_POST['event_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$donation = $_POST['donation'];
$attendees = $_POST['attendees'];
$budget = $_POST['budget'];
$expenses = $_POST['expenses'];

$sql = "UPDATE church_events 
        SET event_name = ?, start_date = ?, end_date = ?, donation = ?, attendees = ?, budget = ?, expenses = ?
        WHERE CHID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiiiis", $event_name, $start_date, $end_date, $donation, $attendees, $budget, $expenses, $CHID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
$stmt->close();
?>