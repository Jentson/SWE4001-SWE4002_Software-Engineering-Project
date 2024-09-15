<?php
session_start();
// Unset student session variables
unset($_SESSION['student_id']);
unset($_SESSION['student_name']);
// Redirect to student login page
header("Location: ../student/LoginForStudent.php");
exit();
?>
