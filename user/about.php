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

    $active = 'about';
}

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
                                src="../assets/img/kaiadmin/logo_light.svg"
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
                            <h3 class="fw-bold mb-3 ms-3">About</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Description</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5 col-md-4">
                                            <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd nav-pills-icons" id="v-pills-tab-with-icon" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active" id="v-pills-home-tab-icons" data-bs-toggle="pill" href="#v-pills-home-icons" role="tab" aria-controls="v-pills-home-icons" aria-selected="true">
                                                    <iconify-icon icon="material-symbols:church" width="3em" height="3em"></iconify-icon><br>
                                                    BPC
                                                </a>
                                                <a class="nav-link" id="v-pills-profile-tab-icons" data-bs-toggle="pill" href="#v-pills-profile-icons" role="tab" aria-controls="v-pills-profile-icons" aria-selected="false" tabindex="-1">
                                                    <iconify-icon icon="icon-park-solid:school" width="3em" height="3em"></iconify-icon><br>
                                                    KKCA
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-7 col-md-8">
                                            <div class="tab-content" id="v-pills-with-icon-tabContent">
                                                <div class="tab-pane fade active show" id="v-pills-home-icons" role="tabpanel" aria-labelledby="v-pills-home-tab-icons">
                                                    <p class="fs-4">Batangas Presbyterian Church (BPC), founded in 1991, is a vibrant and growing community of faith. As a family, BPC warmly welcomes everyone to experience the love of Christ and join in expanding God's horizon through worship, service, and fellowship. With a strong commitment to spiritual growth, outreach, and serving the community.</p>
                                                    <p class="fs-4">BPC invites all to join us in worship, fellowship, and outreach, making a meaningful difference in our local community and beyond.</p>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-profile-icons" role="tabpanel" aria-labelledby="v-pills-profile-tab-icons">
                                                    <p class="fs-4">KKCA, providing education from Pre-Elem to Grade 12, is committed to academic excellence and holistic development. Affiliated with DECS, BI, and PCSN, KKCA offers a nurturing environment where faith and education are integrated.</p>
                                                    <p class="fs-4">As part of the Batangas Presbyterian Church (BPC), KKCA fosters a strong connection between faith and education, empowering students to become responsible and compassionate individuals who contribute to the community and beyond.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-post card-round">
                                <img class="card-img-top" src="../assets/img/bpc-cover.jpg" alt="Card image cap" id="card-img" height="328px">
                                <div class="card-body" id="card-body">
                                    <div class="d-flex">
                                        <div class="avatar">
                                            <img src="../assets/img/BPC-logo.png" alt="..." class="avatar-img rounded-circle" id="avatar-img">
                                        </div>
                                        <div class="info-post ms-2">
                                            <p class="username" id="username">Batangas Presbyterian Church</p>
                                            <p class="date text-muted" id="category">Community</p>
                                        </div>
                                    </div>
                                    <div class="separator-solid"></div>
                                    <h3 class="card-title" id="card-title">
                                        <a href="#"> Intro </a>
                                    </h3>
                                    <p class="card-text" id="card-text">
                                        Batangas Presbyterian Church was founded in 1991,
                                        As a family, we are a growing church. BPC welcomes
                                        everyone to join us in expanding God's Horizon.
                                    </p>
                                    <a href="#" class="btn btn-rounded btn-sm" style="background-color: #203b70; color: #fff;">View Page</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php include("partial/footer.php"); ?>
        </div>

    </div>

    <?php include("partial/script.php"); ?>

    <script>
        document.getElementById("v-pills-profile-tab-icons").addEventListener("click", function() {
            document.getElementById("card-img").src = "../assets/img/kkca-cover.jpg";
            document.getElementById("avatar-img").src = "../assets/img/KKCA-logo.png";
            document.getElementById("username").innerText = "King's Kids Christian Academy ";
            document.getElementById("category").innerText = "Private School";
            document.getElementById("card-title").innerText = "Intro";
            document.getElementById("card-text").innerText = "KKCA was established to provide an educational experience for Pre-Elem up to G-12, DECS, BI, PCSN.";
        });
        document.getElementById("v-pills-home-tab-icons").addEventListener("click", function() {
            document.getElementById("card-img").src = "../assets/img/bpc-cover.jpg";
            document.getElementById("avatar-img").src = "../assets/img/BPC-logo.png";
            document.getElementById("username").innerText = "Batangas Presbyterian Church";
            document.getElementById("category").innerText = "Community";
            document.getElementById("card-title").innerText = "Intro";
            document.getElementById("card-text").innerText = "Batangas Presbyterian Church was founded in 1991, As a family, we are a growing church. BPC welcomes everyone to join us in expanding God 's Horizon.";
        });
    </script>
</body>

</html>