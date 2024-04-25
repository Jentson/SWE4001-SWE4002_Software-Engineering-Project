<?php
session_start();
// Unset staff session variables
unset($_SESSION['Staff_id']);
unset($_SESSION['staff_pass']);
// Redirect to staff login page
header("Location: ../staff/LoginForStaff.php");
exit();
?>
