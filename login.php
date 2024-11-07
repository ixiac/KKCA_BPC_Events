<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    switch ($_SESSION["user_role"]) {
        case 'student':
            header("location: stu/home");
            break;
        case 'admin':
            header("location: adm/home");
            break;
        case 'church_mem':
            header("location: chm/home");
            break;
        case 'staff':
            header("location: staff/home");
            break;
        case 'customer':
            header("location: user/home");
            break;
    }
    exit;
}

require_once "db.php";

$username = $password = $username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Proceed if no errors in username and password
    if (empty($username_err) && empty($password_err)) {
        // Define the user roles and their respective table names
        $user_roles = [
            'student' => 'student',
            'admin' => 'admin',
            'church_mem' => 'church_mem',
            'staff' => 'staff',
            'customer' => 'customer'
        ];

        // Iterate through each role and check if the user exists in that table
        foreach ($user_roles as $role => $table) {

            if ($role == "customer") {
                $user_id = "CID";
            } elseif ($role == "student") {
                $user_id = "SID";
            } elseif ($role == "admin") {
                $user_id = "AID";
            } elseif ($role == "church_mem") {
                $user_id = "CMID";
            } elseif ($role == "staff") {
                $user_id = "SFID";
            }

            $sql = "SELECT $user_id, username, password FROM $table WHERE username = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($password, $hashed_password)) {
                                session_start();

                                // Store session data
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = (string)$id;
                                $_SESSION["username"] = $username;
                                $_SESSION["user_role"] = $role;

                                // Redirect based on the user role
                                switch ($role) {
                                    case 'student':
                                        header("location: stu/home");
                                        break;
                                    case 'admin':
                                        header("location: adm/home");
                                        break;
                                    case 'church_mem':
                                        header("location: chm/home");
                                        break;
                                    case 'staff':
                                        header("location: staff/home");
                                        break;
                                    case 'customer':
                                        header("location: user/home");
                                        break;
                                }
                            } else {
                                $login_err = "Invalid username or password.";
                            }
                        }
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                }
            } else {
                $login_err = "Invalid username or password.";
            }
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("head.php"); ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div class="login-background border-1 shadow-lg text-center">
            <img src="assets/img/login-BG.png" alt="Login Background" class="img-fluid login-bg-image mb-3">
            <div class="login-box container-fluid bg-white p-5 rounded shadow-lg">
                <h1 class="mb-3"><b>LOGIN</b></h1>
                <p class="fs-6">New here? <a class="fs-6" href="signup">Sign-up</a></p>

                <!-- Display error message at the top of the form -->
                <?php if ($login_err): ?>
                    <div class="alert alert-danger"><?= $login_err ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3 me-3 text-end d-flex flex-row column-gap-2 justify-content-end">
                        <p class="fs-6">Forgot Password?
                            <a href="#" class="colog text-decoration-none fs-6">Click here</a>
                        </p>
                    </div>

                    <button type="submit" class="fs-5 btn btn-success">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>