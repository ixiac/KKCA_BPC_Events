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

$events_query = "
    SELECT 
        e.*, 
        CASE 
            WHEN e.event_by LIKE 'AD%' THEN ad.username
            WHEN e.event_by LIKE 'SF%' THEN sf.username
            WHEN e.event_by LIKE 'CM%' THEN cm.username
            WHEN e.event_by LIKE 'S%' THEN s.username
            WHEN e.event_by REGEXP '^[0-9]+$' THEN c.username
            ELSE 'Unknown' 
        END AS event_by_username
    FROM appointment e
    LEFT JOIN admin ad ON e.event_by = ad.AID
    LEFT JOIN staff sf ON e.event_by = sf.SFID
    LEFT JOIN church_mem cm ON e.event_by = cm.CMID
    LEFT JOIN student s ON e.event_by = s.SID
    LEFT JOIN customer c ON e.event_by = c.CID
";

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.datatables.net/v/bm/jqc-1.12.4/dt-2.1.8/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/bm/jqc-1.12.4/dt-2.1.8/datatables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../assets/css/forms.css">

    <link href="../assets/css/calendar.css" rel="stylesheet">
</head>

<body>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: 30%" role="document">
            <div class="modal-content">
                <form id="addEventForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventModalLabel">Add Church Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="eventName">Event Name</label>
                            <input type="text" class="form-control" id="eventName" name="event_name" required>
                        </div>
                        <div class="row px-2">
                            <div class="col-md-6 form-group">
                                <label for="startDate">Start Date</label>
                                <input type="datetime-local" class="form-control" id="startDate" name="start_date" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="endDate">End Date</label>
                                <input type="datetime-local" class="form-control" id="endDate" name="end_date" required>
                            </div>
                        </div>
                        <div class="row px-2">
                            <div class="col-md-6 form-group">
                                <label for="donation">Donation</label>
                                <input type="number" class="form-control" id="donation" name="donation" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="attendees">Attendees</label>
                                <input type="number" class="form-control" id="attendees" name="attendees" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="budget">Budget</label>
                            <input type="number" class="form-control" id="budget" name="budget" required>
                        </div>
                        <div class="form-group">
                            <label for="expenses">Expenses</label>
                            <input type="number" class="form-control" id="expenses" name="expenses" required>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5 mb-3">
                        <button type="submit" class="btn" style="background-color: #00A33C; color: white; width: 25%">Add Event</button>
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
                                <h3 class="fw-bold mb-3 ms-3">Appointments</h3>
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


                    <div class="row pt-5">
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
                                                    <th class="text-center">Event By</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Reference No.</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $events_result->data_seek(0);
                                                while ($event = $events_result->fetch_assoc()) { ?>
                                                    <td class="text-center"><?php echo $event['event_name']; ?></td>
                                                    <td class="text-center"><?php echo $event['event_by_username']; ?></td>
                                                    <td class="text-center"><?php echo $event['category']; ?></td>
                                                    <td class="text-center">₱<?php echo number_format($event['reg_fee'], 2); ?></td>
                                                    <td class="text-center"><?php echo ($event['ref_no']); ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        switch ($event['status']) {
                                                            case '0':
                                                                echo '<span class="badge badge-warning ms-1" style="width: 80%;">Pending</span>';
                                                                break;
                                                            case '1':
                                                                echo '<span class="badge badge-info ms-1" style="width: 80%;">Approved</span>';
                                                                break;
                                                            case '2':
                                                                echo '<span class="badge badge-success ms-1" style="width: 80%;">Completed</span>';
                                                                break;
                                                            case '3':
                                                                echo '<span class="badge badge-danger ms-1" style="width: 80%;">Cancelled</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge badge-secondary ms-1" style="width: 80%;">Unknown</span>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-button-action">
                                                            <button type="button" title="Approve" class="btn btn-link btn-info"
                                                                onclick="approveEvent('<?php echo htmlspecialchars($event['APID']); ?>', '<?php echo htmlspecialchars($event['status']); ?>')">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                            <button type="button" title="Completed" class="btn btn-link btn-success"
                                                                onclick="completeEvent('<?php echo htmlspecialchars($event['APID']); ?>', '<?php echo htmlspecialchars($event['status']); ?>')">
                                                                <i class="fa fa-check-circle"></i>
                                                            </button>
                                                            <button type="button" title="Cancel" class="btn btn-link btn-warning"
                                                                onclick="cancelEvent('<?php echo htmlspecialchars($event['APID']); ?>', '<?php echo htmlspecialchars($event['status']); ?>')">
                                                                <i class="fa fa-ban"></i>
                                                            </button>
                                                            <button type="button" title="Enter Total Cost" class="btn btn-link btn-primary"
                                                                onclick="changeCost('<?php echo htmlspecialchars($event['APID']); ?>', '<?php echo htmlspecialchars($event['total_cost']); ?>')">
                                                                <i class="fas fa-clipboard-check"></i>
                                                            </button>
                                                            <button type="button" title="Remove" class="btn btn-link btn-danger"
                                                                onclick="confirmDelete('<?php echo htmlspecialchars($event['APID']); ?>')">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5"></th>
                                                    <th rowspan="1" colspan="1">
                                                        <select class="form-select" id="status-filter">
                                                            <option value="">All</option>
                                                            <option value="0">Pending</option>
                                                            <option value="1">Approved</option>
                                                            <option value="2">Completed</option>
                                                            <option value="3">Cancelled</option>
                                                        </select>
                                                    </th>
                                                </tr>
                                            </tfoot>
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
                                    targets: [7]
                                }]
                            });
                        });

                        document.getElementById('status-filter').addEventListener('change', function() {
                            const selectedValue = this.value;
                            const table = document.getElementById('multi-filter-select');
                            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                            for (let i = 0; i < rows.length; i++) {
                                const statusCell = rows[i].getElementsByTagName('td')[6];
                                if (statusCell) {
                                    const status = statusCell.textContent.trim();
                                    if (selectedValue === "" || status === getStatusLabel(selectedValue)) {
                                        rows[i].style.display = "";
                                    } else {
                                        rows[i].style.display = "none";
                                    }
                                }
                            }
                        });

                        function getStatusLabel(value) {
                            switch (value) {
                                case "0":
                                    return "Pending";
                                case "1":
                                    return "Approved";
                                case "2":
                                    return "Completed";
                                case "3":
                                    return "Cancelled";
                                default:
                                    return "";
                            }
                        }
                    </script>

                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>

    </div>
    <?php include("partial/script.php"); ?>

    <script>
        function approveEvent(APID, status) {
            if (status == 1) {
                Swal.fire({
                    title: 'Already Approved!',
                    text: 'This event has already been approved.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this event?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "modal/approve_event.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                Swal.fire(
                                    'Approved!',
                                    'The event has been approved.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        };
                        xhr.send("APID=" + APID);
                    }
                });
            }
        }

        function completeEvent(APID, status) {
            if (status == 2) {
                Swal.fire({
                    title: 'Already Completed!',
                    text: 'This event has already been marked as completed.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to mark this event as completed?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, mark it as completed!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "modal/complete_aptevents.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                Swal.fire(
                                    'Completed!',
                                    'The event has been marked as completed.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        };
                        xhr.send("APID=" + APID);
                    }
                });
            }
        }

        function cancelEvent(APID, status) {
            if (status == 3) {
                Swal.fire({
                    title: 'Already Cancelled!',
                    text: 'This event has already been cancelled.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to cancel this event?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "modal/cancel_aptevents.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                Swal.fire(
                                    'Cancelled!',
                                    'The event has been cancelled.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        };
                        xhr.send("APID=" + APID);
                    }
                });
            }
        }

        function changeCost(APID, currentCost) {
            console.log('APID:', APID, 'Current Cost:', currentCost);

            Swal.fire({
                title: 'Change Total Cost',
                input: 'number',
                inputValue: currentCost,
                inputAttributes: {
                    min: 0,
                    step: 'any'
                },
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: (newCost) => {
                    console.log('New Cost:', newCost);
                    if (newCost === "") {
                        Swal.showValidationMessage("Cost cannot be empty");
                        return false;
                    }

                    return fetch('modal/update_cost.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                APID: APID,
                                total_cost: newCost
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);

                            if (data.success) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'The total cost has been updated!',
                                    icon: 'success',
                                    willClose: () => {
                                        setTimeout(() => {
                                            location.reload();
                                        }, 500);
                                    }
                                });
                            } else {
                                Swal.fire('Error', 'Failed to update the cost.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'There was a problem updating the cost.', 'error');
                        });
                }
            });
        }



        function openEditModal(CHID, eventName, startDate, endDate, donation, attendees, budget, expenses) {
            document.getElementById('editEventId').value = CHID;
            document.getElementById('editEventName').value = eventName;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            document.getElementById('editDonation').value = donation;
            document.getElementById('editAttendees').value = attendees;
            document.getElementById('editBudget').value = budget;
            document.getElementById('editExpenses').value = expenses;

            var editEventModal = new bootstrap.Modal(document.getElementById('editEventModal'));
            editEventModal.show();
        }

        document.getElementById('editEventForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const CHID = document.getElementById('editEventId').value;
            const eventName = document.getElementById('editEventName').value;
            const startDate = document.getElementById('editStartDate').value;
            const endDate = document.getElementById('editEndDate').value;
            const donation = document.getElementById('editDonation').value;
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
                    fetch('modal/edit_chevents.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                CHID: CHID,
                                event_name: eventName,
                                start_date: startDate,
                                end_date: endDate,
                                donation: donation,
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
                url: 'modal/delete_aptevents.php',
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

        $('#addEventForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'modal/add_chevents.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Added!',
                            text: res.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: res.message,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!',
                    });
                }
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
                    url: 'modal/ch_calendar.php',
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
                            url: 'modal/update_chevents.php',
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
                                    url: 'modal/delete_chevents.php',
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
                        url: 'modal/update_chevents.php',
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
                        url: 'modal/update_chevents.php',
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