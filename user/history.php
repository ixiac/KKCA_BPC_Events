<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
}

if (isset($_SESSION['id'])) {
    $sql = "SELECT * FROM customer WHERE CID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    } else {
        echo "Error: " . $stmt->error;
    }
}

function getTableByUsername($conn, $username)
{
    $tables = ["student", "admin", "church_mem", "staff", "customer"];
    foreach ($tables as $table) {
        $query = "SELECT * FROM $table WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ["table" => $table, "user_id" => $row["CID"]];
        }
    }
    return null;
}

function fetchUserEvents($conn, $username)
{
    $user_info = getTableByUsername($conn, $username);
    if (!$user_info) {
        echo "User not found.";
        return;
    }

    $table = $user_info["table"];
    $user_id = $user_info["user_id"];

    $events_query = "SELECT event_name, category, start_date, end_date, venue, reg_fee, status 
                     FROM appointment 
                     WHERE event_by = ?";
    $stmt = $conn->prepare($events_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        return $stmt->get_result();
    } else {
        echo "Error fetching events: " . $stmt->error;
        return null;
    }
}

$transaction_query = "SELECT APID, event_name, category, reg_fee, ref_no, ref_img FROM appointment WHERE event_by = ?";
$stmt = $conn->prepare($transaction_query);
$stmt->bind_param("i", $_SESSION['id']);
if ($stmt->execute()) {
    $transaction_result = $stmt->get_result();
} else {
    echo "Error fetching transaction data: " . $stmt->error;
}

$username = $_SESSION["username"];
$events_result = fetchUserEvents($conn, $username);

$entries_per_page = isset($_GET['length']) ? (int)$_GET['length'] : 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_offset = ($current_page - 1) * $entries_per_page;

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointment WHERE event_by = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$total_entries_result = $stmt->get_result();
$total_entries = $total_entries_result->fetch_assoc()['total'];

$total_pages = ceil($total_entries / $entries_per_page);

$stmt = $conn->prepare("SELECT * FROM appointment WHERE event_by = ? LIMIT ?, ?");
$stmt->bind_param("sii", $_SESSION['id'], $start_offset, $entries_per_page);
$stmt->execute();
$events_result = $stmt->get_result();

$active = 'history';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("partial/head.php"); ?>

    <?php include("partial/script.php"); ?>

    <link rel="stylesheet" href="../assets/css/forms.css">

    <link rel="stylesheet" href="../assets/css/ev_tables.css">

    <style>
        .modal-dialog {
            max-height: 80%;
        }
    </style>
</head>

<body>
    <?php include("partial/success_alert.php") ?>

    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <?php include("partial/logo_header.php"); ?>
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>

            <!-- Edit Event Modal -->
            <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editEventForm" method="POST" action="modal/edit_events.php">
                                <input type="hidden" name="APID" id="editEventId">
                                <div class="mb-3">
                                    <label for="editEventName" class="form-label">Event Name</label>
                                    <input type="text" class="form-control" id="editEventName" name="event_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editCategory" class="form-label">Category</label>
                                    <select name="category" class="form-control" id="editCategory">
                                        <option>Wedding</option>
                                        <option>Baptism</option>
                                        <option>Celebrations</option>
                                        <option>Funerals</option>
                                        <option>Community Outreach</option>
                                        <option>Youth Fellowship</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editStartDate" class="form-label">Start Date</label>
                                    <input type="datetime-local" class="form-control" id="editStartDate" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEndDate" class="form-label">End Date</label>
                                    <input type="datetime-local" class="form-control" id="editEndDate" name="end_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editVenue" class="form-label">Venue</label>
                                    <select name="venue" class="form-control" id="editVenue">
                                        <option>BPC Chapel</option>
                                        <option>BPC Open Area</option>
                                        <option>BPC Hall</option>
                                    </select>
                                </div>
                                <div class="row justify-content-center mt-5 mb-3">
                                    <button type="submit" class="btn fs-6" style="width: 30%; color: white; background-color: #00A33C; border-radius: 6px">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="row">
                        <div class="col me-2 d-flex pt-2 pb-4">
                            <a href="home" style="font-size: 20px; margin-top: 3px; color: gray;">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            <h3 class="fw-bold mb-3 ms-3">Event History</h3>
                        </div>
                    </div>


                    <?php if ($events_result && $events_result->num_rows > 0): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="multi-filter-select_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-5">
                                                        <div class="dataTables_length" id="multi-filter-select_length">
                                                            <label>Show
                                                                <select name="multi-filter-select_length" aria-controls="multi-filter-select" class="form-control form-control-sm" onchange="location = this.value;">
                                                                    <option value="?length=10" <?php if ($entries_per_page == 10) echo 'selected'; ?>>10</option>
                                                                    <option value="?length=25" <?php if ($entries_per_page == 25) echo 'selected'; ?>>25</option>
                                                                    <option value="?length=50" <?php if ($entries_per_page == 50) echo 'selected'; ?>>50</option>
                                                                    <option value="?length=100" <?php if ($entries_per_page == 100) echo 'selected'; ?>>100</option>
                                                                </select> entries
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-7">
                                                        <div id="multi-filter-select_filter" class="dataTables_filter">
                                                            <label>Search:
                                                                <input type="search" class="form-control form-control-sm" placeholder="Type Name or Category" aria-controls="multi-filter-select" oninput="searchEvents(this.value)">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive">
                                                            <table id="multi-filter-select" class="display table table-striped table-hover dataTable">
                                                                <thead>
                                                                    <tr role="row">
                                                                        <th class="text-center">Event Name</th>
                                                                        <th class="text-center">Category</th>
                                                                        <th class="text-center">Start Date</th>
                                                                        <th class="text-center">End Date</th>
                                                                        <th class="text-center">Venue</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot>
                                                                    <th colspan="5"></th>
                                                                    <th rowspan="1" colspan="1">
                                                                        <select class="form-select" id="status-filter">
                                                                            <option value=""></option>
                                                                            <option value="0">Pending</option>
                                                                            <option value="1">Approved</option>
                                                                            <option value="2">Ongoing</option>
                                                                            <option value="3">Completed</option>
                                                                            <option value="4">Cancelled</option>
                                                                        </select>
                                                                    </th>
                                                                </tfoot>
                                                                <tbody id="event-table-body">
                                                                    <?php while ($event = $events_result->fetch_assoc()) { ?>
                                                                        <tr class="event-row" data-status="<?php echo $event['status']; ?>" data-apid="<?php echo $event['APID']; ?>">
                                                                            <td class="text-center"><?php echo $event['event_name']; ?></td>
                                                                            <td class="text-center"><?php echo $event['category']; ?></td>
                                                                            <td class="text-center"><?php echo $event['start_date']; ?></td>
                                                                            <td class="text-center"><?php echo $event['end_date']; ?></td>
                                                                            <td class="text-center"><?php echo $event['venue']; ?></td>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                if ($event['status'] == '0') {
                                                                                    echo '<span class="badge badge-warning ms-1" style="width: 60%">Pending</span>';
                                                                                } elseif ($event['status'] == '1') {
                                                                                    echo '<span class="badge badge-warning ms-1" style="width: 60%">Approved</span>';
                                                                                } elseif ($event['status'] == '2') {
                                                                                    echo '<span class="badge badge-danger ms-1" style="width: 60%">Ongoing</span>';
                                                                                } elseif ($event['status'] == '3') {
                                                                                    echo '<span class="badge badge-success ms-1" style="width: 60%">Completed</span>';
                                                                                } elseif ($event['status'] == '4') {
                                                                                    echo '<span class="badge badge-danger ms-1" style="width: 60%">Cancelled</span>';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-button-action">
                                                                                    <button type="button" title="Edit" class="btn btn-link btn-primary btn-lg" onclick="openEditModal(
                                                                                            '<?php echo htmlspecialchars($event['APID']); ?>',
                                                                                            '<?php echo htmlspecialchars($event['event_name']); ?>',
                                                                                            '<?php echo htmlspecialchars($event['category']); ?>',
                                                                                            '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['start_date']))); ?>',
                                                                                            '<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['end_date']))); ?>',
                                                                                            '<?php echo htmlspecialchars($event['venue']); ?>',
                                                                                        )">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </button>
                                                                                    <button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger" onclick="deleteEvent('<?php echo htmlspecialchars($event['APID']); ?>')">
                                                                                        <i class="fa fa-times"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <tr id="no-events-row" style="display: none;">
                                                                        <td colspan="7" class="text-center">No events added with this status.</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-5">
                                                        <div class="dataTables_info" id="multi-filter-select_info" role="status" aria-live="polite">
                                                            Showing <?php echo $start_offset + 1; ?> to <?php echo min($start_offset + $entries_per_page, $total_entries); ?> of <?php echo $total_entries; ?> entries
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-7">
                                                        <div class="dataTables_paginate paging_simple_numbers" id="multi-filter-select_paginate">
                                                            <ul class="pagination">
                                                                <?php if ($current_page > 1): ?>
                                                                    <li class="paginate_button page-item"><a href="?page=<?php echo $current_page - 1; ?>&length=<?php echo $entries_per_page; ?>" class="page-link">Previous</a></li>
                                                                <?php endif; ?>
                                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                    <li class="paginate_button page-item <?php if ($i == $current_page) echo 'active'; ?>">
                                                                        <a href="?page=<?php echo $i; ?>&length=<?php echo $entries_per_page; ?>" class="page-link"><?php echo $i; ?></a>
                                                                    </li>
                                                                <?php endfor; ?>
                                                                <?php if ($current_page < $total_pages): ?>
                                                                    <li class="paginate_button page-item"><a href="?page=<?php echo $current_page + 1; ?>&length=<?php echo $entries_per_page; ?>" class="page-link">Next</a></li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-head-row card-tools-still-right">
                                            <div class="card-title">Transaction History</div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="border-radius: 10px !important">
                                            <table class="table align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" class="text-center">Event Name</th>
                                                        <th scope="col" class="text-center">Category</th>
                                                        <th scope="col" class="text-center">Amount</th>
                                                        <th scope="col" class="text-center">Reference No.</th>
                                                        <th scope="col" class="text-center">Receipt</th>
                                                        <th scope="col" class="text-center">Edit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($transaction_result && $transaction_result->num_rows > 0): ?>
                                                        <?php while ($transaction = $transaction_result->fetch_assoc()): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $transaction['event_name']; ?></td>
                                                                <td class="text-center"><?php echo $transaction['category']; ?></td>
                                                                <td class="text-center">₱<?php echo number_format($transaction['reg_fee'], 2); ?></td>
                                                                <td class="text-center"><?php echo $transaction['ref_no']; ?></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="btn ps-3 pe-2" style="color: #00A33C" data-bs-toggle="modal" data-bs-target="#viewImageModal<?php echo $transaction['ref_no']; ?>">View</a>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-link btn-primary btn-lg" onclick="editTransacModal(
                                                                            '<?php echo htmlspecialchars($transaction['APID']); ?>',
                                                                            '<?php echo htmlspecialchars($transaction['ref_no']); ?>',
                                                                            '<?php echo htmlspecialchars($transaction['ref_img']); ?>'
                                                                        )">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>

                                                            <!-- Receipt Modal -->
                                                            <div class="modal fade" id="viewImageModal<?php echo $transaction['ref_no']; ?>" tabindex="-1" aria-labelledby="viewImageModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="viewImageModalLabel">Image Receipt</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="../assets/ref/<?php echo $transaction['ref_img']; ?>" alt="Transaction Receipt" class="img-fluid">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn" data-bs-dismiss="modal" style="color: white; background-color: #00A33C; border-radius: 6px">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Edit Transaction Modal -->
                                                            <div class="modal fade" id="editTransacModal" tabindex="-1" aria-labelledby="editTransacModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="editTransacModalLabel">Edit Transaction</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form id="editTransacForm" action="modal/update_transac.php" method="POST" enctype="multipart/form-data">
                                                                                <input type="hidden" id="APID" name="APID">
                                                                                <div class="mb-3">
                                                                                    <label for="ref_no" class="form-label">Reference Number</label>
                                                                                    <input type="number" class="form-control" id="ref_no" name="ref_no">
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="ref_img" class="form-label">Receipt Image</label>
                                                                                    <input type="file" class="form-control" id="ref_img" name="ref_img">
                                                                                    <small>Current image: <span id="current_ref_img"></span></small>
                                                                                </div>
                                                                                <input type="hidden" id="current_image" name="current_image">
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn" data-bs-dismiss="modal" style="color: white; background-color: #d33; border-radius: 6px">Cancel</button>
                                                                            <button type="submit" class="btn" form="editTransacForm" style="color: white; background-color: #00A33C; border-radius: 6px">Save changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No transaction history available.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="fs-3 pt-5 text-muted text-center">No events added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    </div>
    <script>
        function searchEvents(query) {
            $.ajax({
                url: 'modal/search_tables.php',
                type: 'GET',
                data: {
                    search: query
                },
                success: function(response) {
                    $('#event-table-body').html(response);
                }
            });
        }

        document.getElementById('status-filter').addEventListener('change', function() {
            const selectedStatus = this.value;
            const rows = document.querySelectorAll('.event-row');
            let hasVisibleRow = false;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');

                if (selectedStatus === '' || rowStatus === selectedStatus) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('no-events-row').style.display = hasVisibleRow ? 'none' : '';
        });

        function openEditModal(APID, eventName, category, startDate, endDate, venue, status) {
            document.getElementById('editEventId').value = APID;
            document.getElementById('editEventName').value = eventName;
            document.getElementById('editCategory').value = category;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            document.getElementById('editVenue').value = venue;

            var editEventModal = new bootstrap.Modal(document.getElementById('editEventModal'));
            editEventModal.show();
        }

        function editTransacModal(APID, ref_no, ref_img) {
            document.getElementById('ref_no').value = ref_no;
            document.getElementById('current_ref_img').textContent = ref_img;
            document.getElementById('current_image').value = ref_img;
            document.getElementById('APID').value = APID;

            $('#editTransacModal').modal('show');
        }

        function deleteEvent(APID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00A33C',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "modal/delete_events.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            Swal.fire(
                                'Deleted!',
                                'The event has been removed.',
                                'success'
                            );

                            var row = document.querySelector(`tr[data-apid="${APID}"]`);
                            if (row) {
                                row.remove();
                            }
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue removing the event.',
                                'error'
                            );
                        }
                    };
                    xhr.send("APID=" + APID);
                }
            });
        }
    </script>
</body>

</html>