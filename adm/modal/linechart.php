<?php
include("../partial/db.php");

$year = $_GET['year'] ?? date('Y');

$months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
];

$query = "
    SELECT MONTHNAME(start_date) AS month, COUNT(*) AS total_events 
    FROM appointment 
    WHERE YEAR(start_date) = ?
    GROUP BY MONTH(start_date) 
    ORDER BY MONTH(start_date)
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $year);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[$row['month']] = $row['total_events'];
    }

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
