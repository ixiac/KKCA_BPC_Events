<?php
session_start();
include("db.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $query = "SELECT * FROM customer WHERE verification_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_query = "UPDATE customer SET is_verified = 1, verification_token = NULL WHERE verification_token = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            echo "Your email has been verified. You can now <a href='login'>log in</a>.";
        } else {
            echo "Error: Could not verify email.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>
