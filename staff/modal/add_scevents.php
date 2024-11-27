<?php
session_start();
include("../partial/db.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $attendees = $_POST['attendees'];
    $budget = $_POST['budget'];
    $expenses = $_POST['expenses'];

    $sql = "INSERT INTO school_events (event_name, start_date, end_date, attendees, budget, expenses) 
            VALUES ('$event_name', '$start_date', '$end_date', '$attendees', '$budget', '$expenses')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }

    $conn->close();
}
?>
