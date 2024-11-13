<?php
session_start();
include("partial/db.php");

// Ensure the user is logged in
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

$events_query = "SELECT * FROM appointment LIMIT $start_offset, $entries_per_page";
$events_result = $conn->query($events_query);

$active = 'history';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include("partial/head.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        input:focus,
        textarea:focus,
        select:focus {
            border: 1px solid #203b70 !important;
            outline: none;
            box-shadow: 0 0 5px rgba(32, 59, 112, 0.5);
        }

        .pagination .page-link {
            color: white;
        }

        .pagination .active .page-link {
            background-color: #203b70 !important;
            color: white !important;
        }

        .pagination .page-item .page-link {
            background-color: transparent;
        }

        .pagination .page-item .page-link:hover {
            background-color: #203b70;
            color: white;
        }

        .no-events-message {
            text-align: center;
            font-size: 24px;
            color: #666;
            margin-top: 50px;
        }
    </style>

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

                    <?php if ($events_result && $events_result->num_rows > 0): ?>
                        <!-- Table with events -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="multi-filter-select_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
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
                                                    <div class="col-sm-12 col-md-6">
                                                        <div id="multi-filter-select_filter" class="dataTables_filter">
                                                            <label>Search:
                                                                <input type="search" class="form-control form-control-sm" placeholder="Type to search" aria-controls="multi-filter-select" oninput="searchEvents(this.value)">
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
                                                                        <th>Event Name</th>
                                                                        <th>Category</th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Venue</th>
                                                                        <th>Reg. Fee</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="event-table-body">
                                                                    <?php while ($event = $events_result->fetch_assoc()): ?>
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
                                                                                    echo '<span class="badge badge-success">Completed</span>';
                                                                                } elseif ($event['status'] == '0') {
                                                                                    echo '<span class="badge badge-warning">Pending</span>';
                                                                                } elseif ($event['status'] == '3') {
                                                                                    echo '<span class="badge badge-danger">Cancelled</span>';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endwhile; ?>
                                                                </tbody>
                                                            </table>
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
                        <div class="no-events-message">
                            <div class="fs-2">You have no events yet.</div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    <?php include("partial/script.php"); ?>
    <script>
        function searchEvents(query) {
            const rows = document.querySelectorAll('#event-table-body tr');
            rows.forEach(row => {
                const eventName = row.cells[0].textContent.toLowerCase();
                if (eventName.includes(query.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</body>

</html>