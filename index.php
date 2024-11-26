<?php
session_start();

$loggedIn = isset($_SESSION["id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>

  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/index.css">

  <link rel="stylesheet" href="assets/css/scroll.css">

  <style>
    .logbtn {
      color: white;
      background-color: #00A33C !important;
    }
  </style>

  <script>
    WebFont.load({
      custom: {
        families: ["Trebuchet MS", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function() {
        sessionStorage.fonts = true;
      },
    });
  </script>

</head>

<body>
  <!-- Nav Bar -->
  <header class="bg-white shadow-sm py-1">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="assets/img/KKCA-logo.png" alt="Logo 1" class="logo me-2" />
          <img src="assets/img/BPC-logo.png" alt="Logo 2" class="logo me-2" />
          <div class="logo-text d-flex flex-column ms-2 fs-4" style="color: black">
            <div>KKCA & BPC</div>
            <div>Event Hub</div>
          </div>
        </a>
        <div class="separator d-none d-lg-block mx-5"></div>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex flex-row" id="navbarNav">
          <ul class="navbar-nav d-flex flex-row column-gap-3">
            <li class="nav-item">
              <a class="nav-link text-dark fw-bold fs-4" href="#home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark fw-bold fs-4" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark fw-bold fs-4" href="#services">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark fw-bold fs-4" href="#contact">Contact</a>
            </li>
          </ul>

          <div class="d-flex ms-auto fs-4">
            <?php if ($loggedIn): ?>
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="user/uploads/default_icon.png" alt="Profile Image" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <span class="fw-bold"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img src="user/uploads/default_icon.png" alt="Profile Image" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4><?= htmlspecialchars(string: $_SESSION['fname']) . " " . htmlspecialchars($_SESSION['lname']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($_SESSION['email']) ?></p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="settings">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            <?php else: ?>
              <form action="signup">
                <button class="btn btn-outline-success me-2 fs-5">Sign-up</button>
              </form>
              <form action="login">
                <button class="logbtn btn fs-5">Log in</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <section id="home">
    <!-- Home Page -->
    <div class="hero d-flex justify-content-start align-items-center text-white">
      <div class="container ms-5 py-5">
        <span class="mb-3" style="font-size: 4rem;"><b>BOOK YOUR RESERVATIONS NOW!</b></span>
        <p class="lead mb-4 h3">
          We, the KKCA and BPC will make sure your event dreams turn into
          reality!<br />
          <span class="d-block mt-2">So book now and enjoy later!</span>
        </p>
        <form action="login">
          <button class="logbtn btn btn-lg">Make an Appointment</button>
        </form>
      </div>
    </div>
  </section>

  <!-- About Page -->
  <section id="about">
    <div class="container-fluid py-5">
      <div class="container py-5">
        <div class="row g-5">
          <div class="col-md-5">
            <div class="bg-light rounded">
              <img
                src="assets/img/about-2.png"
                class="about img-fluid w-100 img-height"
                alt="Image" />
              <img
                src="assets/img/about-3.png"
                class="border-primary img-fluid w-100 border-bottom border-5 img-height"
                style="
                    border-top-right-radius: 300px;
                    border-top-left-radius: 300px;
                  "
                alt="Image" />
            </div>
          </div>
          <div class="col-md-7 wow fadeInRight" data-wow-delay="0.3s">
            <h5 class="sub-title fs-2">About</h5>
            <h1 class="display-7 mb-4 fs-1" style="color: black;">
              <b>We’re The KKCA and BPC Online Event Hub.</b>
            </h1>
            <p class="fs-4 mb-5" style="color: black;">
              Batangas Presbyterian Church was founded in 1991, As a family,
              we are a growing church. BPC welcomes everyone to join us in
              expanding God's Horizon and King's Kid Christian Academy was established to provide an educational experience for Pre-Elem up to G-12, DECS, BI, PCSN.
            </p>
            <div class="row gy-4 align-items-center mt-1">
              <div class="col-4 col-md-3">
                <div class="bg-primary text-light text-center rounded p-3">
                  <i class="bi bi-calendar" style="font-size: 2.6em;"></i>
                  <h1 class="display-5 fw-bold mb-2" style="font-size: 4rem;">32</h1>
                  <p class="mb-1" style="font-size: 1.3rem;">Years of Experience</p>
                </div>
              </div>
              <div class="col-8 col-md-9">
                <div class="mt-4">
                  <p class="text-primary h4 mb-3">
                    Offer 100 % Genuine Assistance
                  </p>
                  <p class="text-primary h4 mb-3">
                    Has Faster & Reliable Execution
                  </p>
                  <p class="text-primary h4 mb-3">
                    Fast & Accurate Booking
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Page -->
  <section id="services" class="py-5">
    <div class="container-fluid py-5">
      <div class="container">
        <div class="text-center mb-5">
          <h1 class="display-4 fw-bold pb-3" style="color: #203b70;">Our Services</h1>
          <p class="fs-5 text-muted pb-5">
            We provide a wide range of services tailored to make your events memorable.
          </p>
        </div>
        <div class="row justify-content-center align-items-center g-5">
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing">
              <div class="card-header">
                <h4 class="card-title">Wedding</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-dark">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing">
              <div class="card-header">
                <h4 class="card-title">Baptism</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-dark">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing">
              <div class="card-header">
                <h4 class="card-title">Receptions</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-dark">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing">
              <div class="card-header">
                <h4 class="card-title">Celebrations</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-dark">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row justify-content-center align-items-center g-5">
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing card-primary">
              <div class="card-header">
                <h4 class="card-title">Youth Fellowship</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-white">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing card-primary">
              <div class="card-header">
                <h4 class="card-title">Funerals</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-white">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3 pe-md-0">
            <div class="card card-pricing card-primary">
              <div class="card-header">
                <h4 class="card-title">Community Outreach</h4>
                <div class="card-price">
                  <span class="price">15,000</span>
                  <span class="text text-white">+</span>
                </div>
              </div>
              <div class="card-body">
                <ul class="specification-list">
                  <li>
                    <span class="name-specification">Negotiable</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">Down Payment</span>
                    <span class="status-specification">5,000-10,000</span>
                  </li>
                  <li>
                    <span class="name-specification">Venue</span>
                    <span class="status-specification">BPC Chapel</span>
                  </li>
                  <li>
                    <span class="name-specification">Coordinators</span>
                    <span class="status-specification">Yes</span>
                  </li>
                  <li>
                    <span class="name-specification">On-site Assistance</span>
                    <span class="status-specification">Yes</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-5 mx-5">
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
  </section>

  <!-- Contact Page -->
  <section id="contact-page" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="color: #00A33C;">Get In Touch</h1>
        <p class="fs-4 text-muted">Feel free to reach out to us for any queries or assistance. We're here to help!</p>
      </div>
      <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-6">
          <div class="card-primary p-5 rounded shadow">
            <form>
              <div class="mb-4">
                <label for="name" class="form-label fw-bold">Your Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter your name" />
              </div>
              <div class="mb-4">
                <label for="email" class="form-label fw-bold">Your Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" />
              </div>
              <div class="mb-4">
                <label for="message" class="form-label fw-bold">Your Message</label>
                <textarea class="form-control" id="message" rows="5" placeholder="Type your message"></textarea>
              </div>
              <button type="submit" class="btn btn-lg text-white" style="background-color: #00A33C;">Send Message</button>
            </form>
          </div>
        </div>

        <!-- Contact Details -->
        <div class="col-lg-6">
          <div class="bg-white p-5 rounded shadow">
            <h3 class="fw-bold mb-4" style="color: #00A33C;">Contact Information</h3>
            <p class="fs-5 mb-3"><i class="bi bi-geo-alt-fill me-3"></i> Gloria Marris St. Cuta, Batangas City</p>
            <p class="fs-5 mb-3"><i class="bi bi-envelope-fill me-3"></i> kkca.bpc.events@gmail.com</p>
            <p class="fs-5 mb-3"><i class="bi bi-telephone-fill me-3"></i> +0916 743 6785</p>
            <hr class="my-4">
            <h3 class="fw-bold mb-4" style="color: #00A33C;">Follow Us</h3>
            <div>
              <a href="#" class="text-dark fs-4 me-3"><i class="bi bi-facebook"></i></a>
              <a href="#" class="text-dark fs-4 me-3"><i class="bi bi-twitter"></i></a>
              <a href="#" class="text-dark fs-4 me-3"><i class="bi bi-instagram"></i></a>
              <a href="#" class="text-dark fs-4"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Footer Page -->
  <section id="contact">
    <div class="container-fluid footer py-5">
      <div class="container-fluid py-5 justify-content-center">
        <div class="row g-5 text-center justify-content-center">
          <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="footer-item d-flex flex-column align-items-center">
              <h3 class="foothead mb-4">Contact Info</h3>
              <a href=""><i class="fas fa-location-arrow me-2"></i>Cuta, Batangas City</a>
              <a href=""><i class="fas fa-envelope me-2"></i>kkca.bpc.events</a>
              <a href=""><i class="fas fa-phone me-2"></i> +0916 743 6785</a>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="footer-item d-flex flex-column align-items-center">
              <h3 class="foothead mb-4">Opening Time</h3>
              <div class="mb-3">
                <h5 class="text-white mb-0">Mon - Friday:</h5>
                <p class="text-white mb-0">09.00 am to 07.00 pm</p>
              </div>
              <div class="mb-3">
                <h5 class="text-white mb-0">Saturday:</h5>
                <p class="text-white mb-0">10.00 am to 05.00 pm</p>
              </div>
              <div class="mb-3">
                <h5 class="text-white mb-0">Vacation:</h5>
                <p class="text-white mb-0">All Sunday is our vacation</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="footer-item d-flex flex-column align-items-center">
              <h3 class="foothead mb-4">Our Services</h3>
              <h4 class="text-white mb-2">Wedding</h4>
              <h4 class="text-white mb-2">Baptism</h4>
              <h4 class="text-white mb-2">Celebrations</h4>
              <h4 class="text-white mb-2">Funerals</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include('assets/js/script.php'); ?>
</body>

</html>