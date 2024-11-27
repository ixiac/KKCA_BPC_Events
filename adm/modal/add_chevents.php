<?php
session_start();
include("../partial/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $donation = $_POST['donation'];
    $attendees = $_POST['attendees'];
    $budget = $_POST['budget'];
    $expenses = $_POST['expenses'];
    $event_by = $_SESSION['id'];

    $query = "INSERT INTO church_events (event_name, start_date, end_date, donation, attendees, budget, expenses, event_by)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiiiis', $event_name, $start_date, $end_date, $donation, $attendees, $budget, $expenses, $event_by);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event successfully added!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add event.']);
    }

    $stmt->close();
    $conn->close();
}
?>