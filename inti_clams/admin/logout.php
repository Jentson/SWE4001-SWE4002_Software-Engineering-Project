<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page or any other desired location
header("Location: ../validation/login.php");
exit();
?>
