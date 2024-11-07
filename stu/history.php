<?php
session_start();
include("partial/db.php");

// Ensure the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
}

// Check if `id` exists in session and fetch the user data
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

// Function to get table name based on username
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
            return ["table" => $table, "user_id" => $row["SID"]]; // adjust field name for user ID in each table if needed
        }
    }
    return null; // No matching user found
}

// Fetch events based on username
function fetchUserEvents($conn, $username)
{
    $user_info = getTableByUsername($conn, $username);
    if (!$user_info) {
        echo "User not found.";
        return;
    }

    $table = $user_info["table"];
    $user_id = $user_info["user_id"];

    // Query to fetch events by user_id
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

// Fetch events for the logged-in user
$username = $_SESSION["username"]; // Ensure username is stored in session upon login
$events_result = fetchUserEvents($conn, $username);

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
                            <img
                                src="assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
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
                    <!-- End Logo Header -->
                </div>
                <?php include("partial/navbar.php"); ?>
            </div>

            <div class="container" style="background-color: #dbdde0 !important;">
                <div class="page-inner">
                    <div class="row">
                        <div class="col me-2 d-flex pt-2 pb-4">
                            <a href="home"
                                style="font-size: 20px; margin-top: 3px; color: gray;">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            <h3 class="fw-bold mb-3 ms-3">Event History</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="multi-filter-select_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="dataTables_length" id="multi-filter-select_length"><label>Show <select name="multi-filter-select_length" aria-controls="multi-filter-select" class="form-control form-control-sm">
                                                                <option value="10">10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select> entries</label></div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div id="multi-filter-select_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="multi-filter-select"></label></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="table-responsive">
                                                        <table id="multi-filter-select" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="multi-filter-select_info">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Event Name: activate to sort column ascending" style="width: 280px">Event Name</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Category: activate to sort column ascending" aria-sort="descending">Category</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending">Start Date</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending">End Date</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Venue: activate to sort column ascending">Venue</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Registration Fee: activate to sort column ascending">Reg. Fee</th>
                                                                    <th class="sorting" tabindex="0" aria-controls="multi-filter-select" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php while ($event = $events_result->fetch_assoc()) { ?>
                                                                    <tr>
                                                                        <td><?php echo $event['event_name']; ?></td>
                                                                        <td><?php echo $event['category']; ?></td>
                                                                        <td><?php echo $event['start_date']; ?></td>
                                                                        <td><?php echo $event['end_date']; ?></td>
                                                                        <td><?php echo $event['venue']; ?></td>
                                                                        <td>₱<?php echo number_format($event['reg_fee'], 2); ?></td>
                                                                        <td>
                                                                            <?php
                                                                            if ($event['status'] == '1') {
                                                                                echo '<span class="badge badge-success" style="width: 80px;">Completed</span>';
                                                                            } elseif ($event['status'] == '0') {
                                                                                echo '<span class="badge badge-warning" style="width: 80px;">Pending</span>';
                                                                            } elseif ($event['status'] == '3') {
                                                                                echo '<span class="badge badge-danger" style="width: 80px;">Cancelled</span>';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-5">
                                                    <div class="dataTables_info" id="multi-filter-select_info" role="status" aria-live="polite">Showing 1 to 3 of 3 entries</div>
                                                </div>
                                                <div class="col-sm-12 col-md-7">
                                                    <div class="dataTables_paginate paging_simple_numbers" id="multi-filter-select_paginate">
                                                        <ul class="pagination">
                                                            <!-- <li class="paginate_button page-item previous disabled" id="multi-filter-select_previous"><a href="#" aria-controls="multi-filter-select" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li> -->
                                                            <li class="paginate_button page-item active"><a href="#" aria-controls="multi-filter-select" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                                            <!-- <li class="paginate_button page-item "><a href="#" aria-controls="multi-filter-select" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                                            <li class="paginate_button page-item "><a href="#" aria-controls="multi-filter-select" data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
                                                            <li class="paginate_button page-item "><a href="#" aria-controls="multi-filter-select" data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
                                                            <li class="paginate_button page-item "><a href="#" aria-controls="multi-filter-select" data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
                                                            <li class="paginate_button page-item disabled" id="multi-filter-select_ellipsis"><a href="#" aria-controls="multi-filter-select" data-dt-idx="6" tabindex="0" class="page-link">…</a></li>
                                                            <li class="paginate_button page-item "><a href="#" aria-controls="multi-filter-select" data-dt-idx="7" tabindex="0" class="page-link">12</a></li>
                                                            <li class="paginate_button page-item next" id="multi-filter-select_next"><a href="#" aria-controls="multi-filter-select" data-dt-idx="8" tabindex="0" class="page-link">Next</a></li> -->
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
                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    </div>
    <?php include("partial/script.php"); ?>
</body>

</html>