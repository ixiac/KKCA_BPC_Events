<?php

session_start();
include("partial/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_no'])) {
    header("Location: index");
    exit();
}

// Fetch user data from the database
$user_no = $_SESSION['user_no'];
$query = "SELECT fname, lname, email, username, age FROM accounts WHERE user_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
} else {
    header("Location: index");
    exit();
}

// Fetch events from the "ch_events" table
$events_query = "SELECT * FROM ch_events";
$events_stmt = $conn->prepare($events_query);
$events_stmt->execute();
$events_result = $events_stmt->get_result();

$active = 'history';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>
</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="home" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="row">
                        <div class="col me-2 d-flex pt-2 pb-4">
                            <a href="home" style="font-size: 20px; margin-top: 3px; color: gray;">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            <h3 class="fw-bold mb-3 ms-3">Church Events</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Events</h4>
                                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                            <i class="fa fa-plus"></i> Add Event
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Modal for Adding Event -->
                                    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Add New Event</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="modal/add_events.php" method="POST">
                                                        <input type="hidden" name="source" value="ch_events">
                                                        <input type="hidden" name="return_url" value="../ch_events.php">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label>Event Name</label>
                                                                <input type="text" name="event_name" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Category</label>
                                                                <input type="text" name="category" class="form-control" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Start Date</label>
                                                                        <input type="datetime-local" name="start_date" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>End Date</label>
                                                                        <input type="datetime-local" name="start_date" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Venue</label>
                                                                <input type="text" name="venue" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Registration Fee</label>
                                                                <input type="number" name="reg_fee" class="form-control" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select name="status" class="form-control">
                                                                    <option value="pending">Pending</option>
                                                                    <option value="completed">Completed</option>
                                                                    <option value="cancelled">Cancelled</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer border-0">
                                                                <button type="submit" class="btn btn-primary">Add Event</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for Editing Event -->
                                    <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Edit Event</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editEventForm" action="modal/edit_events.php" method="POST">
                                                        <input type="hidden" name="event_id" id="editEventId">
                                                        <input type="hidden" name="return_url" value="../ch_events.php">
                                                        <div class="form-group">
                                                            <label>Event Name</label>
                                                            <input type="text" name="event_name" class="form-control" id="editEventName" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" name="category" class="form-control" id="editCategory" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Start Date</label>
                                                                    <input type="datetime-local" name="start_date" class="form-control" id="editStartDate" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>End Date</label>
                                                                    <input type="datetime-local" name="end_date" class="form-control" id="editEndDate" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Venue</label>
                                                            <input type="text" name="venue" class="form-control" id="editVenue" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Registration Fee</label>
                                                            <input type="number" name="reg_fee" class="form-control" id="editRegFee" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select name="status" class="form-control" id="editStatus">
                                                                <option value="pending">Pending</option>
                                                                <option value="completed">Completed</option>
                                                                <option value="cancelled">Cancelled</option>
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                        <input type="hidden" name="source" id="eventSource">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Events Table -->
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Event Name</th>
                                                    <th>Category</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Venue</th>
                                                    <th>Registration Fee</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($event = $events_result->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($event['category']); ?></td>
                                                        <td><?php echo htmlspecialchars($event['start_date']); ?></td>
                                                        <td><?php echo htmlspecialchars($event['end_date']); ?></td>
                                                        <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                                        <td><?php echo htmlspecialchars($event['reg_fee']); ?></td>
                                                        <td>
                                                            <?php
                                                            if ($event['status'] == 'completed') {
                                                                echo '<span class="badge badge-success" style="width: 80px;">Completed</span>';
                                                            } elseif ($event['status'] == 'pending') {
                                                                echo '<span class="badge badge-warning" style="width: 80px;">Pending</span>';
                                                            } elseif ($event['status'] == 'cancelled') {
                                                                echo '<span class="badge badge-danger" style="width: 80px;">Cancelled</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button type="button" class="btn btn-link btn-primary btn-lg" onclick="openEditModal(
                                                                    '<?php echo htmlspecialchars($event['EID']); ?>',
                                                                    '<?php echo htmlspecialchars($event['event_name']); ?>',
                                                                    '<?php echo htmlspecialchars($event['category']); ?>',
                                                                    '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['start_date']))); ?>',
                                                                    '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['end_date']))); ?>',
                                                                    '<?php echo htmlspecialchars($event['venue']); ?>',
                                                                    '<?php echo htmlspecialchars($event['reg_fee']); ?>',
                                                                    '<?php echo htmlspecialchars($event['status']); ?>',
                                                                    'ch_events'
                                                                )">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>

    <?php include("partial/script.php"); ?>
    <script>
        function openEditModal(eventId, eventName, category, startDate, endDate, venue, regFee, status, source) {
            document.getElementById('editEventId').value = eventId; // Set the event_id
            document.getElementById('editEventName').value = eventName;
            document.getElementById('editCategory').value = category;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            document.getElementById('editVenue').value = venue;
            document.getElementById('editRegFee').value = regFee;

            // Set the selected status in the dropdown
            const editStatusDropdown = document.getElementById('editStatus');
            editStatusDropdown.value = status;

            // Set the source
            document.getElementById('eventSource').value = source;

            // Show the edit modal
            $('#editEventModal').modal('show');
        }
    </script>


</html>