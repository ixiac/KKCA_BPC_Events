<?php
session_start();
include("../partial/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = isset($_POST['event_name']) ? $_POST['event_name'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $venue = isset($_POST['venue']) ? $_POST['venue'] : null;
    $reg_fee = isset($_POST['reg_fee']) ? $_POST['reg_fee'] : null;
    $ref_no = isset($_POST['ref_no']) ? $_POST['ref_no'] : null;

    if (isset($_FILES['ref_img']) && $_FILES['ref_img']['error'] == UPLOAD_ERR_OK) {
        $uploads_dir = '../uploads/';
        $filename = basename($_FILES['ref_img']['name']);
        $file_path = $uploads_dir . $filename;

        if (move_uploaded_file($_FILES['ref_img']['tmp_name'], $file_path)) {
            $ref_img = $filename;
        } else {
            echo "Error uploading file.";
            exit;
        }
    } else {
        $ref_img = null;
    }

    switch ($category) {
        case "Wedding":
            $exp_cost = 15000;
            break;
        case "Baptism":
            $exp_cost = 3000;
            break;
        case "Celebrations":
            $exp_cost = 10000;
            break;
        case "Funerals":
            $exp_cost = 10000;
            break;
        case "Community Outreach":
            $exp_cost = 5000;
            break;
        case "Youth Fellowship":
            $exp_cost = 5000;
            break;
        default:
            $exp_cost = 0;
            break;
    }

    if ($event_name && $category && $start_date && $end_date && $venue && $reg_fee && $ref_no) {
        $sql = "INSERT INTO appointment (event_name, event_by, category, start_date, end_date, venue, reg_fee, ref_no, ref_img, exp_cost) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssisss", $event_name, $_SESSION['id'], $category, $start_date, $end_date, $venue, $reg_fee, $ref_no, $ref_img, $exp_cost);

        if ($stmt->execute()) {
            $_SESSION['swal_message'] = [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Your appointment has been successfully submitted.'
            ];

            header("Location: ../appointment.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
