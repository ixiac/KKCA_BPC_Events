<?php
session_start();

$loggedIn = isset($_SESSION["id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />

  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

  <link rel="stylesheet" href="assets/css/index.css" />

  <style>
    .logbtn {
      color: white;
      background-color: #00A33C !important;
    }
  </style>

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
          <div class="col-md-5 wow fadeInLeft" data-wow-delay="0.1s">
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

  <!-- Footer Page -->
  <section id="contact">
    <div class="container-fluid footer py-5">
      <div class="container-fluid py-5 justify-content-center">
        <div class="row g-5 text-center justify-content-center">
          <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="footer-item d-flex flex-column align-items-center">
              <h3 class="foothead mb-4">Contact Info</h3>
              <a href=""><i class="fas fa-location-arrow me-2"></i> Gloria Marris St. Cuta, Batangas City</a>
              <a href=""><i class="fas fa-envelope me-2"></i>g.batangas@gmail.com</a>
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