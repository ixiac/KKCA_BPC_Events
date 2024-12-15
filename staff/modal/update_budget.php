<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../partial/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $APID = $data['APID'];
    $budget = $data['exp_cost'];

    $query = "UPDATE appointment SET exp_cost = ? WHERE APID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('di', $budget, $APID);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>