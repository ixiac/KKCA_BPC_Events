<?php
session_start();
include("../partial/db.php");

if (!isset($_SESSION['user_no'])) {
    header("Location: ../index");
    exit();
}

$return_url = ''; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $category = $_POST['category'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $venue = $_POST['venue'];
    $reg_fee = $_POST['reg_fee'];
    $status = $_POST['status'];
    $source = $_POST['source'];
    $return_url = $_POST['return_url'];

    // Set the return URL based on the source
    if ($source === 'ch_events') {
        $return_url = '../ch_events.php'; // Update this path based on your project structure
        $query = "UPDATE ch_events SET event_name=?, category=?, start_date=?, end_date=?, venue=?, reg_fee=?, status=? WHERE EID=?";
    } elseif ($source === 'sc_events') {
        $return_url = '../sc_events.php'; // Update this path based on your project structure
        $query = "UPDATE sc_events SET event_name=?, category=?, start_date=?, end_date=?, venue=?, reg_fee=?, status=? WHERE EID=?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssdsi", $event_name, $category, $start_date, $end_date, $venue, $reg_fee, $status, $event_id);

    if ($stmt->execute()) {
        header("Location: $return_url");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
