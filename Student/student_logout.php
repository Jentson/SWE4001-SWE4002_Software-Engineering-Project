<?php
session_start();
// Unset student session variables
unset($_SESSION['stud_id']);
unset($_SESSION['stud_name']);
// Redirect to student login page
header("Location: ../Student/LoginForStudent.html");
exit();
?>
