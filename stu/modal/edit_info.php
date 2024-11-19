<?php
include("../partial/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$field = $data['field'];
$value = $data['value'];
$userId = $data['userId'];

$allowedFields = ['fname', 'lname', 'tel_no', 'address', 'sex', 'username', 'email', 'age'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
}

$sql = "UPDATE church_mem SET $field = ? WHERE CMID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $value, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
