<?php

session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM staff WHERE SFID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["SFID"];
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

    <link rel="stylesheet" href="../assets/css/forms.css">

    <link href="../assets/css/calendar.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <?php include("partial/logo-header.php"); ?>
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
                                <h3 class="fw-bold mb-3 ms-3">Event History</h3>
                            </div>
                        </div>
                        <div class="ms-md-auto pb-3">
                            <a data-bs-toggle="modal" data-bs-target="#sc_calendarModal" class="btn" style="color: white; background-color: #203b70; border-radius: 10px">View Calendar</a>
                        </div>
                    </div>

                    <div class="modal fade" id="sc_calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
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
                            <select id="yearFilter" class="form-control text-muted" style="width: 10%; background-color: #dbdde0; border: 1px solid gray">
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
                        <a data-bs-toggle="modal" data-bs-target="#sc_calendarModal" class="btn" style="color: white; background-color: #203b70; border-radius: 10px">View Calendar</a>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="multi-filter-select" class="table table-striped table-hover dataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Event Name</th>
                                                    <th class="text-center">Start Date</th>
                                                    <th class="text-center">End Date</th>
                                                    <th class="text-center">Attendees</th>
                                                    <th class="text-center">Budget</th>
                                                    <th class="text-center">Expenses</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $events_result->data_seek(0);
                                                while ($event = $events_result->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $event['event_name']; ?></td>
                                                        <td class="text-center"><?php echo date('F j, Y, g:i A', strtotime($event['start_date'])); ?></td>
                                                        <td class="text-center"><?php echo date('F j, Y, g:i A', strtotime($event['end_date'])); ?></td>
                                                        <td class="text-center"><?php echo $event['attendees']; ?></td>
                                                        <td class="text-center"><?php echo $event['budget']; ?></td>
                                                        <td class="text-center"><?php echo $event['expenses']; ?></td>
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
                                "lengthChange": true
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
                    $('#eventStart').val(event.start.toISOString().slice(0, 16)); // Local time format
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