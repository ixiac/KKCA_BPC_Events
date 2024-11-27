<?php
include("../partial/db.php");

$sql = "
    SELECT 
        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS approved,
        SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS cancelled,
        COUNT(*) AS total
    FROM appointment
";
$result = mysqli_query($conn, $sql);

$data = [
    'pending' => 0,
    'approved' => 0,
    'completed' => 0,
    'cancelled' => 0,
    'total' => 0
];

if ($result && $row = mysqli_fetch_assoc($result)) {
    $data['pending'] = $row['pending'];
    $data['approved'] = $row['approved'];
    $data['completed'] = $row['completed'];
    $data['cancelled'] = $row['cancelled'];
    $data['total'] = $row['total'];
}

echo json_encode($data);
?>