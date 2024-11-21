<?php
include("../partial/db.php");

$query = "SELECT MONTHNAME(start_date) AS month, COUNT(*) AS total_events FROM school_events GROUP BY MONTH(start_date) ORDER BY MONTH(start_date);
";

$result = mysqli_query($conn, $query);

if ($result) {
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($events);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
}
?>