<?php
include("../partial/db.php");

$sql = "
    SELECT category, COUNT(*) as count
    FROM appointment
    WHERE category IN ('Wedding', 'Baptism', 'Receptions', 'Celebrations', 'Funerals', 'Community outreach', 'Youth fellowship')
    GROUP BY category
";

$result = mysqli_query($conn, $sql);

$data = [
    'categories' => [],
    'counts' => []
];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data['categories'][] = $row['category'];
        $data['counts'][] = $row['count'];
    }
}

echo json_encode($data);
?>