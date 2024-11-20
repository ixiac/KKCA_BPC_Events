<?php
include('../partial/db.php');

$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT * FROM school_events WHERE event_name LIKE '%$search_query%'";
$events_result = $conn->query($sql);

if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        echo '<tr class="event-row">';
        echo '<td class="text-center">' . $event['event_name'] . '</td>';
        echo '<td class="text-center">' . $event['start_date'] . '</td>';
        echo '<td class="text-center">' . $event['end_date'] . '</td>';
        echo '<td class="text-center">' . $event['attendees'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7" class="text-center">No event matches your search</td></tr>';
}
?>