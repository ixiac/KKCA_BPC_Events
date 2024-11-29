<?php
session_start();
include("../partial/db.php");

if (isset($_POST['APID'])) {
    $APID = $_POST['APID'];

    $sql = "UPDATE appointment SET status = 2 WHERE APID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $APID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Event marked as completed successfully.";
    } else {
        echo "Failed to mark event as completed.";
    }

    $stmt->close();
    $conn->close();
}
?>