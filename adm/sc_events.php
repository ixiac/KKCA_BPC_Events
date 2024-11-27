<?php

session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM admin WHERE AID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["AID"];
}

$events_query = "SELECT * FROM school_events";
$events_stmt = $conn->prepare($events_query);
$events_stmt->execute();
$events_result = $events_stmt->get_result();

$active = 'history';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

    <link href="https://cdn.datatables.net/v/bm/jqc-1.12.4/dt-2.1.8/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/bm/jqc-1.12.4/dt-2.1.8/datatables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../assets/css/forms.css">

    <link href="../assets/css/calendar.css" rel="stylesheet">
</head>

<body>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: 30%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" id="editEventId" name="SCID">
                        <div class="mb-5 px-3">
                            <label for="editEventName" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="editEventName" name="eventName" required>
                        </div>
                        <div class="row mb-3 px-3">
                            <div class="col-md-6">
                                <label for="editStartDate" class="form-label">Start Date & Time</label>
                                <input type="datetime-local" class="form-control" id="editStartDate" name="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editEndDate" class="form-label">End Date & Time</label>
                                <input type="datetime-local" class="form-control" id="editEndDate" name="endDate" required>
                            </div>
                        </div>
                        <div class="mb-3 px-3">
                            <label for="editAttendees" class="form-label">Attendees</label>
                            <input type="number" class="form-control" id="editAttendees" name="attendees" required>
                        </div>
                        <div class="mb-3 px-3">
                            <label for="editBudget" class="form-label">Budget</label>
                            <input type="number" class="form-control" id="editBudget" name="budget" required>
                        </div>
                        <div class="mb-3 px-3">
                            <label for="editExpenses" class="form-label">Expenses</label>
                            <input type="number" class="form-control" id="editExpenses" name="expenses" required>
                        </div>
                        <div class="row justify-content-center mt-5 mb-3">
                            <button type="submit" class="btn" style="background-color: #00A33C; color: white; width: 25%" form="editEventForm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: 30%">
            <div class="modal-content">
                <form id="addEventForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 px-3">
                            <label for="event_name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="event_name" name="event_name" required>
                        </div>
                        <div class="row mb-3 px-1">
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
                        <div class="mb-3 px-3">
                            <label for="attendees" class="form-label">Attendees</label>
                            <input type="number" class="form-control" id="attendees" name="attendees" required>
                        </div>
                        <div class="mb-3 px-3">
                            <label for="budget" class="form-label">Budget</label>
                            <input type="number" class="form-control" id="budget" name="budget" required>
                        </div>
                        <div class="mb-3 px-3">
                            <label for="expenses" class="form-label">Expenses</label>
                            <input type="number" class="form-control" id="expenses" name="expenses" required>
                        </div>
                    </div>
                    <div class="row justify-content-center pb-5">
                        <button type="submit" class="btn" style="background-color: #00a33c; width: 25%; color: white;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="../index.html" class="logo">
                            <img src="../assets/img/BPC-logo.png" alt="navbar brand" class="navbar-brand" height="20">
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-1 pb-0">
                        <div class="row">
                            <div class="col d-flex pt-2">
                                <a href="home"
                                    style="font-size: 20px; margin-top: 3px; color: gray;">
                                    <i class="fas fa-arrow-left me-2"></i>
                                </a>
                                <h3 class="fw-bold mb-3 ms-3">School Events</h3>
                            </div>
                        </div>
                        <div class="ms-md-auto pb-3">
                            <a data-bs-toggle="modal" data-bs-target="#sc_calendarModal" class="btn" style="color: white; background-color: #203b70; border-radius: 10px">View Calendar</a>
                        </div>
                    </div>

                    <div class="modal fade" id="sc_calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: 80%;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="calendarModalLabel">Event Calendar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="scroll overflow-y-scroll p-2" style="max-height: 84vh">
                                        <div id="sc_calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 50%">
                            <div class="modal-content" style="background-color: #f2f2f2">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventModalLabel">Edit Event</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="eventTitle">Event Title</label>
                                            <input type="text" class="form-control" id="eventTitle" placeholder="Enter event title">
                                        </div>
                                        <div class="form-group">
                                            <label for="eventStart">Start Date</label>
                                            <input type="datetime-local" class="form-control" id="eventStart">
                                        </div>
                                        <div class="form-group">
                                            <label for="eventEnd">End Date</label>
                                            <input type="datetime-local" class="form-control" id="eventEnd">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                                    <button type="button" class="btn btn-primary" id="saveEvent">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group ps-3">
                            <label for="yearFilter" class="text-muted">Filter by:</label>
                            <select id="yearFilter" class="form-control text-muted" style="width: 5%; background-color: #dbdde0; border: 1px solid gray;">
                                <option value="">Year</option>
                                <?php
                                $years = [];
                                $events_result->data_seek(0);
                                while ($event = $events_result->fetch_assoc()) {
                                    $year = date('Y', strtotime($event['start_date']));
                                    if (!in_array($year, $years)) {
                                        $years[] = $year;
                                        echo "<option value=\"$year\">$year</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a class="btn btn-round float-end" style="color: white; background-color: #203b70;"
                                        data-bs-toggle="modal" data-bs-target="#addEventModal">
                                        Add Events
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="multi-filter-select" class="table table-striped table-hover dataTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Event Name</th>
                                                    <th class="text-center">Start Date</th>
                                                    <th class="text-center">End Date</th>
                                                    <th class="text-center">Attendees</th>
                                                    <th class="text-center">Budget</th>
                                                    <th class="text-center">Expenses</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $events_result->data_seek(0);
                                                while ($event = $events_result->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $event['event_name']; ?></td>
                                                        <td class="text-center">
                                                            <span style="display: none;"><?php echo strtotime($event['start_date']); ?></span>
                                                            <?php echo date('F j, Y, g:i A', strtotime($event['start_date'])); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span style="display: none;"><?php echo strtotime($event['end_date']); ?></span>
                                                            <?php echo date('F j, Y, g:i A', strtotime($event['end_date'])); ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $event['attendees']; ?></td>
                                                        <td class="text-center"><?php echo $event['budget']; ?></td>
                                                        <td class="text-center"><?php echo $event['expenses']; ?></td>
                                                        <td class="text-center">
                                                            <div class="form-button-action">
                                                                <button type="button" title="Edit" class="btn btn-link btn-lg" style="color: #203b70;"
                                                                    onclick="openEditModal(
                                                                        '<?php echo htmlspecialchars($event['SCID']); ?>',
                                                                        '<?php echo htmlspecialchars($event['event_name']); ?>',
                                                                        '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['start_date']))); ?>',
                                                                        '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['end_date']))); ?>',
                                                                        '<?php echo htmlspecialchars($event['attendees']); ?>',
                                                                        '<?php echo htmlspecialchars($event['budget']); ?>',
                                                                        '<?php echo htmlspecialchars($event['expenses']); ?>'
                                                                    )">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" title="Remove" class="btn btn-link btn-danger"
                                                                    onclick="confirmDelete('<?php echo htmlspecialchars($event['SCID']); ?>')">
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


                    <script>
                        $(document).ready(function() {
                            $('#multi-filter-select').DataTable({
                                "paging": true,
                                "searching": true,
                                "ordering": true,
                                "info": true,
                                "lengthChange": true,
                                columnDefs: [{
                                    orderable: false,
                                    targets: [6]
                                }]
                            });

                            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                                var selectedYear = $('#yearFilter').val();
                                var startDate = data[1];
                                var eventYear = new Date(startDate).getFullYear();

                                return selectedYear === "" || eventYear == selectedYear;
                            });

                            $('#yearFilter').on('change', function() {
                                table.draw();
                            });
                        });
                    </script>

                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>

    </div>
    <?php include("partial/script.php"); ?>

    <script>
        function openEditModal(SCID, eventName, startDate, endDate, attendees, budget, expenses) {
            document.getElementById('editEventId').value = SCID;
            document.getElementById('editEventName').value = eventName;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            document.getElementById('editAttendees').value = attendees;
            document.getElementById('editBudget').value = budget;
            document.getElementById('editExpenses').value = expenses;

            var editEventModal = new bootstrap.Modal(document.getElementById('editEventModal'));
            editEventModal.show();
        }

        document.getElementById('editEventForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const SCID = document.getElementById('editEventId').value;
            const eventName = document.getElementById('editEventName').value;
            const startDate = document.getElementById('editStartDate').value;
            const endDate = document.getElementById('editEndDate').value;
            const attendees = document.getElementById('editAttendees').value;
            const budget = document.getElementById('editBudget').value;
            const expenses = document.getElementById('editExpenses').value;

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00A33C',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('modal/edit_events.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                SCID: SCID,
                                event_name: eventName,
                                start_date: startDate,
                                end_date: endDate,
                                attendees: attendees,
                                budget: budget,
                                expenses: expenses
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                bootstrap.Modal.getInstance(document.getElementById('editEventModal')).hide();

                                Swal.fire({
                                    title: 'Saved!',
                                    text: 'Your changes have been saved.',
                                    icon: 'success',
                                    confirmButtonColor: '#00A33C',
                                });

                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                Swal.fire('Error!', 'Failed to save changes.', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error!', 'An error occurred while saving.', 'error');
                        });
                }
            });
        });

        function confirmDelete(eventId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00a33c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteEvent(eventId);
                }
            });
        }

        function deleteEvent(eventId) {
            $.ajax({
                url: 'modal/delete_scevents.php',
                type: 'POST',
                data: {
                    id: eventId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            });
        }

        document.getElementById('addEventForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('modal/add_scevents.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Added',
                            text: 'The event has been successfully added!',
                            confirmButtonColor: '#203b70'
                        }).then(() => {
                            document.querySelector('#addEventModal .btn-close').click();
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to add the event. Please try again.',
                            confirmButtonColor: '#203b70'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.',
                        confirmButtonColor: '#203b70'
                    });
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('sc_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'today next'
                },
                events: {
                    url: 'modal/sc_calendar.php',
                    method: 'GET',
                    failure: function() {
                        Swal.fire('Error', 'Failed to load events!', 'error');
                    },
                    success: function(data) {
                        console.log("Loaded events:", data);
                    }
                },
                locale: 'en',
                timeZone: 'Asia/Manila',
                eventColor: '#00A33C',
                editable: true,
                eventClick: function(info) {
                    var event = info.event;

                    $('#eventModal').modal('show');
                    $('#eventTitle').val(event.title);
                    $('#eventStart').val(event.start.toISOString().slice(0, 16));
                    $('#eventEnd').val(event.end ? event.end.toISOString().slice(0, 16) : event.start.toISOString().slice(0, 16));

                    $('#saveEvent').off('click').on('click', function() {
                        var updatedTitle = $('#eventTitle').val();
                        var updatedStart = $('#eventStart').val();
                        var updatedEnd = $('#eventEnd').val();

                        $.ajax({
                            url: 'modal/update_scevents.php',
                            method: 'POST',
                            data: {
                                id: event.id,
                                title: updatedTitle,
                                start: updatedStart,
                                end: updatedEnd
                            },
                            success: function(response) {
                                $('#eventModal').modal('hide');
                                Swal.fire('Success', 'Event updated successfully!', 'success');
                                calendar.refetchEvents();
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to update event.', 'error');
                            }
                        });
                    });

                    $('#deleteEvent').off('click').on('click', function() {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This will permanently delete the event.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: 'modal/delete_scevents.php',
                                    method: 'POST',
                                    data: {
                                        id: event.id
                                    },
                                    success: function(response) {
                                        $('#eventModal').modal('hide');
                                        Swal.fire('Deleted!', 'Your event has been deleted.', 'success');
                                        calendar.refetchEvents();
                                    },
                                    error: function() {
                                        Swal.fire('Error', 'Failed to delete event.', 'error');
                                    }
                                });
                            }
                        });
                    });
                },
                eventDrop: function(info) {
                    $.ajax({
                        url: 'modal/update_scevents.php',
                        method: 'POST',
                        data: {
                            id: info.event.id,
                            title: info.event.title,
                            start: info.event.start.toISOString(),
                            end: info.event.end ? info.event.end.toISOString() : null
                        },
                        success: function(response) {
                            Swal.fire('Success', 'Event moved successfully!', 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to move event.', 'error');
                        }
                    });
                },
                eventResize: function(info) {
                    $.ajax({
                        url: 'modal/update_scevents.php',
                        method: 'POST',
                        data: {
                            id: info.event.id,
                            start: info.event.start.toISOString(),
                            end: info.event.end ? info.event.end.toISOString() : null
                        },
                        success: function(response) {
                            Swal.fire('Success', 'Event resized successfully!', 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to resize event.', 'error');
                        }
                    });
                }
            });

            $('#sc_calendarModal').on('shown.bs.modal', function() {
                calendar.render();
            });
        });
    </script>
</body>

</html>