<?php

session_start();
include("partial/db.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index");
    exit;
} else {
    $sql = "SELECT * FROM customer WHERE CID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row["CID"];

    $sqlDate = "SELECT start_date, end_date FROM appointment";
    $result = $conn->query($sqlDate);

    $unavailableDates = [];
    while ($date = $result->fetch_assoc()) {
        $unavailableDates[] = [
            "start_date" => $date['start_date'],
            "end_date" => $date['end_date']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php include("partial/head.php"); ?>

    <style>
        input:focus,
        textarea:focus,
        select:focus {
            border: 1px solid #203b70 !important;
            outline: none;
            box-shadow: 0 0 5px rgba(32, 59, 112, 0.5);
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <?php
    if (isset($_SESSION['swal_message'])) {
        $swalType = $_SESSION['swal_message']['type'];
        $swalTitle = $_SESSION['swal_message']['title'];

        echo "<script>
            Swal.fire({
                title: '$swalTitle',
                confirmButtonText: 'OK'
            });
        </script>";

        unset($_SESSION['swal_message']);
    }
    ?>

    <div class="wrapper">
        <?php include("partial/sidebar.php"); ?>
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.php" class="logo">
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
                            <h3 class="fw-bold mb-3 ms-3">Appointment</h3>
                        </div>
                    </div>
                    <style>
                        .carousel-item img {
                            height: 51vh;
                        }
                    </style>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-round">
                                <div class="card-body px-5">
                                    <form id="appointmentForm" action="modal/submit_appointment.php" method="POST" enctype="multipart/form-data">
                                        <div class="row px-3 pb-3">
                                            <div class="col-md-6 form-group">
                                                <label for="event_name">Event Name</label>
                                                <input type="text" class="form-control" id="event_name" name="event_name" placeholder="Name your Event">
                                            </div>
                                        </div>
                                        <div class="row px-3 pb-3">
                                            <div class="col-md-6 form-group">
                                                <label for="categorySelect">Category</label>
                                                <select class="form-select" id="category-select" name="category" onchange="updateDownPayment()">
                                                    <option>Wedding</option>
                                                    <option>Baptism</option>
                                                    <option>Celebrations</option>
                                                    <option>Funerals</option>
                                                    <option>Community Outreach</option>
                                                    <option>Youth Fellowship</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="venueSelect">Venue</label>
                                                <select class="form-select" id="venue-select" name="venue">
                                                    <option>BPC Chapel</option>
                                                    <option>BPC Open Area</option>
                                                    <option>Malitam Brgy. Hall</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row px-3 pb-3">
                                            <div class="col-md-6 form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" onchange="setEndDateMin()">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                                            </div>
                                        </div>
                                        <div class="row px-3 pb-3">
                                            <div class="col-md-4 form-group">
                                                <label for="regFee">Registration Fee</label>
                                                <input type="number" class="form-control" id="registration-fee" name="reg_fee" placeholder="Registration Fee">
                                                <small id="down-payment-text" class="form-text text-muted ps-2">Down Payment: ₱5,000 - ₱15,000</small>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="ref_no">Reference No.</label>
                                                <input type="number" class="form-control" id="reference-no" name="ref_no" placeholder="Reference No.">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="exampleFormControlFile1">Reference Image</label>
                                                <input type="file" class="form-control-file mt-2" id="exampleFormControlFile1" name="ref_img">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mx-2 mt-3">
                                            <button type="button" onclick="confirmSubmission()" class="btn fs-5" style="width: 30%; color: white; background-color: #00A33C; border-radius: 6px">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="fs-3">Venue Images</div>
                            <div id="appointmentCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
                                <div class="carousel-inner" style="border-radius: 10px">
                                    <div class="carousel-item active">
                                        <img src="venue/venue_1.jpg" class="d-block w-100" alt="Image 1">
                                        <div class="text-center">BPC Chapel Figure 1</div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="venue/venue_2.jpg" class="d-block w-100" alt="Image 2">
                                        <div class="text-center">BPC Chapel Figure 2</div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="venue/venue_3.jpg" class="d-block w-100" alt="Image 2">
                                        <div class="text-center">BPC Open Court Figure 1</div>
                                    </div>
                                    <div class="carousel-item">
                                        <img src="venue/venue_4.jpg" class="d-block w-100" alt="Image 2">
                                        <div class="text-center">BPC Open Court Figure 2</div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#appointmentCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#appointmentCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="fs-3">How Appointment Works?</div>
                        <ul class="timeline">
                            <li><br></li>
                            <li>
                                <div class="timeline-badge">
                                    <i class="far fa-paper-plane"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Submit your Appointment</h4>
                                        <p>
                                            <small class="text-muted"><i class="far fa-paper-plane"></i> Delivered</small>
                                        </p>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            Start the process by filling out the appointment request form on our system. Ensure that all required information, such as the purpose of the appointment, your preferred date and time, and payment details, is accurately entered. Double-check your entries to minimize delays in processing.
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-inverted">
                                <div class="timeline-badge warning">
                                    <i class="far fa-bell"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Notification</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            Upon submission, our staff will immediately receive a notification about your appointment request. This alert allows them to promptly begin the review process, helping ensure that your appointment is scheduled and processed without unnecessary delays.
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-badge danger">
                                    <i class="far fa-check-circle"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Staff Review</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            Our dedicated staff reviews the submitted details to confirm all information is accurate and complete. They check the availability of requested dates and ensure any necessary resources or personnel are scheduled. If additional details are needed, staff may reach out to you for clarification.
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-inverted">
                                <div class="timeline-badge info">
                                    <i class="far fa-calendar-check"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Appointment Confirmation</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            After successful review, your appointment is officially confirmed. You will receive a notification with details about your appointment. Be sure to save this confirmation as it serves as your entry pass on the meeting.
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <!-- Step 5: Prepare for Meeting -->
                            <li>
                                <div class="timeline-badge">
                                    <i class="far fa-folder-open"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Prepare for Meeting</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            In advance of your scheduled meeting, make sure to gather any documents or information necessary for the appointment. This could include personal identification, application forms, or previous records, depending on the appointment type. Preparing in advance ensures a smooth, efficient meeting.
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <!-- Final Step: Offline Meeting -->
                            <li class="timeline-inverted">
                                <div class="timeline-badge success">
                                    <i class="far fa-handshake"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Offline Meeting</h4>
                                    </div>
                                    <div class="timeline-body">
                                        <p>
                                            On the day of your meeting, meet with our staff in person at the specified location. This face-to-face meeting is a chance to discuss your needs in detail, address any questions, and finalize necessary arrangements. We look forward to meeting with you and assisting with your request in person.
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <?php include("partial/footer.php"); ?>
        </div>
    </div>
    </div>
    <?php include("partial/script.php"); ?>

    <script>
        function updateDownPayment() {
            const category = document.getElementById("category-select").value;
            const downPaymentText = document.getElementById("down-payment-text");

            let downPayment;

            switch (category) {
                case "Wedding":
                    downPayment = "₱5,000 - ₱15,000";
                    break;
                case "Baptism":
                    downPayment = "₱500 - ₱3,000";
                    break;
                case "Celebrations":
                    downPayment = "₱2,000 - ₱10,000";
                    break;
                case "Funerals":
                    downPayment = "₱3,000 - ₱10,000";
                    break;
                case "Community Outreach":
                    downPayment = "₱2,000 - ₱5,000";
                    break;
                case "Youth Fellowship":
                    downPayment = "₱1,000 - ₱5,000";
                    break;
                default:
                    downPayment = "Please select a category";
                    break;
            }
            downPaymentText.textContent = `Down payment: ${downPayment}`;
        }

        const unavailableDates = <?php echo json_encode($unavailableDates); ?>;

        function disableUnavailableDates() {
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");

            unavailableDates.forEach(range => {
                const start = new Date(range.start_date);
                const end = new Date(range.end_date);

                startDateInput.addEventListener("input", () => {
                    const selectedDate = new Date(startDateInput.value);
                    if (selectedDate >= start && selectedDate <= end) {
                        alert("This date is unavailable. Please select a different start date.");
                        startDateInput.value = "";
                    }
                });

                endDateInput.addEventListener("input", () => {
                    const selectedDate = new Date(endDateInput.value);
                    if (selectedDate >= start && selectedDate <= end) {
                        alert("This date is unavailable. Please select a different end date.");
                        endDateInput.value = "";
                    }
                });
            });
        }

        disableUnavailableDates();

        function setEndDateMin() {
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");

            endDateInput.min = startDateInput.value;
        }

        function confirmSubmission() {
            // Get all input fields
            const eventName = document.getElementById('event_name').value.trim();
            const category = document.getElementById('category-select').value.trim();
            const venue = document.getElementById('venue-select').value.trim();
            const startDate = document.getElementById('start_date').value.trim();
            const endDate = document.getElementById('end_date').value.trim();
            const regFee = document.getElementById('registration-fee').value.trim();
            const refNo = document.getElementById('reference-no').value.trim();
            const refImg = document.getElementById('exampleFormControlFile1').value.trim();

            if (!eventName || !category || !venue || !startDate || !endDate || !regFee || !refNo || !refImg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill in all fields before submitting.',
                    confirmButtonColor: '#d33'
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit this appointment?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00A33C',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('appointmentForm').submit();
                    }
                });
            }
        }
    </script>

</body>

</html>