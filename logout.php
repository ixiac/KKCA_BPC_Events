<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the home page (index.php)
header("Location: index");
exit();
?>