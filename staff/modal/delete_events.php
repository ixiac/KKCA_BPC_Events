<?php
session_start();
include("../partial/db.php");

if (!isset($_SESSION['user_no'])) {
    header("Location: ../index");
    exit();
}

$user_no = $_SESSION['user_no'];

if (isset($_POST['delete_event_id'])) {
    $event_id = $_POST['delete_event_id'];
    $source = $_POST['source'];
    $return_url = $_POST['return_url'];

    // Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Determine the source table and event_from value based on the source input
        if ($source === "ch_events") {
            $source_table = "ch_events";
            $event_from = "church";
        } elseif ($source === "sc_events") {
            $source_table = "sc_events";
            $event_from = "school";
        } else {
            throw new Exception("Invalid source provided.");
        }

        // Retrieve the event details from the source table before deletion
        $select_query = "SELECT * FROM $source_table WHERE EID = ?";
        $stmt = $conn->prepare($select_query);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event_data = $result->fetch_assoc();

        if ($event_data) {
            // Insert event data into ar_events with the determined event_from value
            $insert_query = "INSERT INTO ar_events (event_name, category, start_date, end_date, venue, reg_fee, status, event_from, event_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param(
                "sssssisi",
                $event_data['event_name'],
                $event_data['category'],
                $event_data['start_date'],
                $event_data['end_date'],
                $event_data['venue'],
                $event_data['reg_fee'],
                $event_data['status'],
                $event_from,
                $user_no
            );
            $insert_stmt->execute();

            // Delete the event from the source table
            $delete_query = "DELETE FROM $source_table WHERE EID = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $event_id);
            $delete_stmt->execute();

            // Commit the transaction
            $conn->commit();
            $_SESSION['message'] = "Event deleted and archived successfully!";
        } else {
            throw new Exception("Event not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Failed to delete event: " . $e->getMessage();
    }

    header("Location: $return_url");
    exit();
}
?>
