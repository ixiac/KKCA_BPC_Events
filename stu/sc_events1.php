<?php
session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
}

if (isset($_SESSION['id'])) {
    $sql = "SELECT * FROM student WHERE SID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['id']);

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
            return ["table" => $table, "user_id" => $row["SID"]];
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

    $events_query = "SELECT * FROM school_events";
    $stmt = $conn->prepare($events_query);

    if ($stmt->execute()) {
        return $stmt->get_result();
    } else {
        echo "Error fetching events: " . $stmt->error;
        return null;
    }
}

$username = $_SESSION["username"];
$events_result = fetchUserEvents($conn, $username);

$entries_per_page = isset($_GET['length']) ? (int)$_GET['length'] : 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_offset = ($current_page - 1) * $entries_per_page;

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM school_events");
$stmt->execute();
$total_entries_result = $stmt->get_result();
$total_entries = $total_entries_result->fetch_assoc()['total'];

$total_pages = ceil($total_entries / $entries_per_page);

$stmt = $conn->prepare("SELECT * FROM school_events LIMIT ?, ?");
$stmt->bind_param("ii",  $start_offset, $entries_per_page);
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
                                                                        <th class="text-center">Start Date</th>
                                                                        <th class="text-center">End Date</th>
                                                                        <th class="text-center">Attendees</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="event-table-body">
                                                                    <?php while ($event = $events_result->fetch_assoc()) { ?>
                                                                        <tr class="event-row">
                                                                            <td class="text-center"><?php echo $event['event_name']; ?></td>
                                                                            <td class="text-center"><?php echo $event['start_date']; ?></td>
                                                                            <td class="text-center"><?php echo $event['end_date']; ?></td>
                                                                            <td class="text-center"><?php echo $event['attendees']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
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
                url: 'modal/search_sctables.php',
                type: 'GET',
                data: {
                    search: query
                },
                success: function(response) {
                    $('#event-table-body').html(response);
                }
            });
        }
    </script>
</body>

</html>