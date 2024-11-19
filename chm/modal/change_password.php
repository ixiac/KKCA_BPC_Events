<?php
include("../partial/db.php");

$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$sql = "UPDATE church_mem SET password = ? WHERE CMID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $password, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
