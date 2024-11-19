<?php
include("../partial/db.php");
$uploadDir = '../uploads/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['profile'];
    $userId = $_POST['userId'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (in_array($file['type'], $allowedTypes)) {
            $fileName = uniqid() . '-' . basename($file['name']);
            $filePath = $uploadDir . $fileName;

            $filePathInDb = str_replace('../', '', $filePath);

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $query = "UPDATE church_mem SET profile = ? WHERE CMID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ss', $filePathInDb, $userId);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'newImagePath' => $filePathInDb]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading file']);
    }
}
?>
