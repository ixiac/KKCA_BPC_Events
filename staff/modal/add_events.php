<?php
session_start();
include("../partial/db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_no'])) {
    header("Location: index");
    exit();
}

// Get the current user's user_no
$user_no = $_SESSION['user_no'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $event_name = $_POST['event_name'];
    $category = $_POST['category'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $venue = $_POST['venue'];
    $reg_fee = $_POST['reg_fee'];
    $status = $_POST['status'];
    $source = $_POST['source'];
    $return_url = $_POST['return_url'];

    // Determine which table to insert into based on the source
    if ($source === 'ch_events') {
        $query = "INSERT INTO ch_events (event_name, category, event_by, start_date, end_date, venue, reg_fee, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    } elseif ($source === 'sc_events') {
        $query = "INSERT INTO sc_events (event_name, category, event_by, start_date, end_date, venue, reg_fee, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssdsi", $event_name, $category, $user_no, $start_date, $end_date, $venue, $reg_fee, $status);

    if ($stmt->execute()) {
        // Redirect to event history page or show a success message
        header("Location: $return_url");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
