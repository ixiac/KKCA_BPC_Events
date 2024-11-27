<?php
include("../partial/db.php");

$sql = "
    SELECT 
        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN status = 5 THEN 1 ELSE 0 END) AS cancelled,
        COUNT(*) AS total
    FROM appointment WHERE MONTH(date_created) = MONTH(CURRENT_DATE) AND YEAR(date_created) = YEAR(CURRENT_DATE)
";
$result = mysqli_query($conn, $sql);

$data = [
    'pending' => 0,
    'completed' => 0,
    'cancelled' => 0,
    'total' => 0
];

if ($result && $row = mysqli_fetch_assoc($result)) {
    $data['pending'] = $row['pending'];
    $data['completed'] = $row['completed'];
    $data['cancelled'] = $row['cancelled'];
    $data['total'] = $row['total'];
}

echo json_encode($data);
?>