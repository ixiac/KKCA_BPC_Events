<?php
session_start();
include("db.php");

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $sex = isset($_POST['sex']) && $_POST['sex'] == '1' ? 1 : 0;
    $tel_no = $_POST['tel_no'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $email = $_POST['email'] . '@gmail.com';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (empty($fname) || empty($lname) || empty($username) || empty($tel_no) || empty($age) || empty($address) || empty($email) || empty($password)) {
        $error = 'Please fill all fields';
    } else {
        $check_user_query = "SELECT * FROM customer WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_user_query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            $insert_query = "INSERT INTO customer (username, password, fname, lname, sex, address, tel_no, age, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssssissis", $username, $password, $fname, $lname, $sex, $address, $tel_no, $age, $email);

            if ($stmt->execute()) {
                $success = 'Account created successfully! Redirecting...';

                echo "<script>
                        setTimeout(function(){
                        window.location.href = 'login';
                        }, 1000);
                    </script>";
            } else {
                $error = 'Error: Could not create account';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php'); ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="assets/css/signup.css">
</head>

<body>
    <div class="container-fluid d-flex justify-content-center align-items-center">
        <div class="login-background border-1 shadow-lg text-center">
            <img src="assets/img/login-BG.png" alt="Login Background" class="img-fluid login-bg-image mb-3">
            <div class="login-box container-fluid bg-white p-5 rounded">
                <h1 class="mb-1"><b>SIGN-UP</b></h1>
                <p class="fs-6 mb-1">Have an account? <a class="fs-6" href="login.php">Log-in</a></p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-1 row">
                        <div class="col-md-6 form-group">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-md-6 form-group">
                            <label>Gender</label><br>
                            <div class="row">
                                <div class="col form-check ps-5">
                                    <input class="form-check-input" type="radio" name="sex" value="0" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1"> Male </label>
                                </div>
                                <div class="col form-check">
                                    <input class="form-check-input" type="radio" name="sex" value="1" id="flexRadioDefault2" checked>
                                    <label class="form-check-label" for="flexRadioDefault2"> Female </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="telno">Tel/Phone No.</label>
                            <input type="number" class="form-control" id="tel_no" name="tel_no" placeholder="09XXXXXXXXX" required>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-md-9 form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="col-3 form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-1 form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group mb-1">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                <span class="input-group-text">@gmail.com</span>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                        </div>
                    </div>
                    <div class="mb-2 form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="fs-5 btn btn-success">Sign-up</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>