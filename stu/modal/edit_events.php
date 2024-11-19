<?php
session_start();
include('../partial/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $APID = $_POST['APID'];
    $event_name = $_POST['event_name'];
    $category = $_POST['category'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $venue = $_POST['venue'];

    $sql = "UPDATE appointment SET event_name = ?, category = ?, start_date = ?, end_date = ?, venue = ? WHERE APID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssssi', $event_name, $category, $start_date, $end_date, $venue, $APID);

        if ($stmt->execute()) {
            $_SESSION['swal_message'] = [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Your event has been edited.'
            ];

            header("Location: ../history.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        header("Location: home.php?error=Database error");
    }
}
?>