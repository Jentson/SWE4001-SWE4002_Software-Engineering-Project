<?php
session_start();

if (!isset($_SESSION['student_id']) && !isset($_SESSION['Staff_id'])) {
    echo '<script>alert("Please Login!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}
?>

