<?php
include("../partial/db.php");

$months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

$query = "
    SELECT MONTHNAME(start_date) AS month, COUNT(*) AS total_events 
    FROM appointment 
    GROUP BY MONTH(start_date) 
    ORDER BY MONTH(start_date)";
$result = mysqli_query($conn, $query);

if ($result) {
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[$row['month']] = $row['total_events'];
    }

    // Fill missing months with 0
    $completeData = [];
    foreach ($months as $month) {
        $completeData[] = [
            'month' => $month,
            'total_events' => $events[$month] ?? 0
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($completeData);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
}
?>