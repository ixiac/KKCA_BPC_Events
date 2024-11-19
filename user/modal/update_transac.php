<?php
session_start();
include('../partial/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $APID = $_POST['APID'];
    $ref_no = $_POST['ref_no'];
    $new_image = $_FILES['ref_img']['name'];
    $image_tmp = $_FILES['ref_img']['tmp_name'];

    if ($new_image) {
        $upload_dir = '../../assets/ref/';
        $image_path = $upload_dir . basename($new_image);

        if (move_uploaded_file($image_tmp, $image_path)) {
        } else {
            echo "Failed to upload image.";
            exit;
        }
    } else {
        $image_path = $_POST['current_image'];
    }

    $stmt = $conn->prepare("UPDATE appointment SET ref_img = ?, ref_no = ? WHERE APID = ?");

    $stmt->bind_param("sis", $image_path, $ref_no, $APID);

    if ($stmt->execute()) {
        $_SESSION['swal_message'] = [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Your event has been edited.'
        ];

        header("Location: ../history.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
