<?php
include('../partial/db.php');

$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT * FROM appointment WHERE event_name LIKE '%$search_query%' OR category LIKE '%$search_query%'";
$events_result = $conn->query($sql);

if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $event['event_name'] . '</td>';
        echo '<td>' . $event['category'] . '</td>';
        echo '<td>' . $event['start_date'] . '</td>';
        echo '<td>' . $event['end_date'] . '</td>';
        echo '<td>' . $event['venue'] . '</td>';
        echo '<td>₱' . number_format($event['reg_fee'], 2) . '</td>';
        echo '<td>';
        if ($event['status'] == '1') {
            echo '<span class="badge badge-success">Completed</span>';
        } elseif ($event['status'] == '0') {
            echo '<span class="badge badge-warning">Pending</span>';
        } elseif ($event['status'] == '3') {
            echo '<span class="badge badge-danger">Cancelled</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7" class="text-center">No event matches your search</td></tr>';
}
?>