<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $APID = $data['APID'];
    $total_cost = $data['total_cost'];

    $query = "UPDATE appointment SET total_cost = ? WHERE APID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('di', $total_cost, $APID);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>