<?php
include("../partial/db.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Encrypt the password

// Update query to change password
$sql = "UPDATE customer SET password = ? WHERE CID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $password, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
