<?php
session_start();
include('../partial/db.php');

if (isset($_POST['APID'])) {
    $APID = $_POST['APID'];

    $query = "DELETE FROM appointment WHERE APID = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $APID);
        if ($stmt->execute()) {
            echo "Event removed successfully!";
        } else {
            echo "Error removing event.";
        }
        $stmt->close();
    } else {
        echo "Database query failed.";
    }

    $conn->close();
} else {
    echo "No APID received.";
}
?>
