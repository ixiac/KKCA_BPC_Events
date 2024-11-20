<?php
include('../partial/db.php');
session_start();

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$user_id) {
    echo '<tr><td colspan="7" class="text-center">User not logged in</td></tr>';
    exit;
}

$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';


$sql = "SELECT * FROM appointment WHERE (event_name LIKE '%$search_query%' OR category LIKE '%$search_query%') AND event_by = '$user_id'";

$events_result = $conn->query($sql);

if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        echo '<tr class="event-row" data-status="' . htmlspecialchars($event['status']) . '" data-apid="' . htmlspecialchars($event['APID']) . '">';
        echo '<td class="text-center">' . htmlspecialchars($event['event_name']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($event['category']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($event['start_date']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($event['end_date']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($event['venue']) . '</td>';
        echo '<td class="text-center">₱' . number_format($event['reg_fee'], 2) . '</td>';
        echo '<td class="text-center">';
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
