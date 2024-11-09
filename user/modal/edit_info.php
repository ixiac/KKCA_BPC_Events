<?php
include("../partial/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$field = $data['field'];
$value = $data['value'];
$userId = $data['userId'];

// Validate input to prevent SQL injection
$allowedFields = ['fname', 'lname', 'tel_no', 'address', 'sex', 'username', 'email', 'age'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
}

// Prepare the SQL statement using `mysqli`
$sql = "UPDATE customer SET $field = ? WHERE CID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $value, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
