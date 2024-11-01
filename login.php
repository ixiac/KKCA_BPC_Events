<?php

session_start();
include("db.php"); // Database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists in the database
    $sql = "SELECT * FROM accounts WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user information in session variables
            $_SESSION['user_no'] = $user['user_no'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type']; // Store user type in session
            
            // Redirect based on user type
            if ($user['user_type'] == 'customer') {
                header("Location: user/home");
            } elseif ($user['user_type'] == 'church_mem') {
                header("Location: chm/home");
            } elseif ($user['user_type'] == 'student') {
                header("Location: stu/home");
            } elseif ($user['user_type'] == 'staff') {
                header("Location: staff/home");
            } else {
                $error = "User type is not recognized";
            }
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
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
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
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
