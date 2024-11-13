<?php
session_start();
include("../partial/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $reg_fee = mysqli_real_escape_string($conn, $_POST['reg_fee']);
    $ref_no = mysqli_real_escape_string($conn, $_POST['ref_no']);
    $event_by = $_SESSION['id'];

    $ref_img = $_FILES['ref_img']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($ref_img);
    move_uploaded_file($_FILES['ref_img']['tmp_name'], $target_file);

    $sql = "INSERT INTO appointment (event_name, event_by, category, start_date, end_date, venue, reg_fee, ref_no, ref_img)
            VALUES ('$event_name', '$event_by', '$category', '$start_date', '$end_date', '$venue', '$reg_fee', '$ref_no', '$target_file')";

    if (mysqli_query($conn, $sql)) {
        echo "Appointment successfully created!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
