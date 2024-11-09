<?php
include("../partial/db.php");
$uploadDir = '../uploads/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['profile'];
    $userId = $_POST['userId'];

    // Check if a file was uploaded
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        if (in_array($file['type'], $allowedTypes)) {
            $fileName = uniqid() . '-' . basename($file['name']);
            $filePath = $uploadDir . $fileName;

            // Remove '../' from the file path before saving it to the database
            $filePathInDb = str_replace('../', '', $filePath);

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $query = "UPDATE customer SET profile = ? WHERE CID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('si', $filePathInDb, $userId);
                if ($stmt->execute()) {
                    // Return success and the new image path to update the frontend
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
