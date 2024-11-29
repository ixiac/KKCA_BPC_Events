<?php
include("../partial/db.php");

$year = $_GET['year'] ?? date('Y');

$sql = "
    SELECT category, COUNT(*) as count
    FROM appointment
    WHERE YEAR(start_date) = ?
    AND category IN ('Wedding', 'Baptism', 'Receptions', 'Celebrations', 'Funerals', 'Community outreach', 'Youth fellowship')
    GROUP BY category
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'categories' => [],
    'counts' => []
];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data['categories'][] = $row['category'];
        $data['counts'][] = (int) $row['count'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>