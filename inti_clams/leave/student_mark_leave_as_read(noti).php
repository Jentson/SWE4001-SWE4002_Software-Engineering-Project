<?php
session_start();
require_once "../database/db.php";

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    exit; // or redirect to login page
}

$studentId = $_SESSION['student_id'];

// Update the studentIsRead column in the lecturer_approval table for the logged-in student
$query = "UPDATE lecturer_approval AS la
          JOIN leave_application AS laa ON la.leave_id = laa.leave_id
          SET la.studentIsRead = 1 
          WHERE laa.student_id = '$studentId' 
            AND la.process = 1 
            AND la.studentIsRead = 0";
$result = mysqli_query($con, $query);

if ($result) {
    echo "Success";
} else {
    echo "Error";
}
?>
