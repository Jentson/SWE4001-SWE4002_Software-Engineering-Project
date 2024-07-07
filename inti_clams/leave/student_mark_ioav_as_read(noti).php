<?php
session_start();
require_once "../database/db.php";

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    exit; // or redirect to login page
}

$studentId = $_SESSION['student_id'];

$query = "UPDATE ioav_approval 
          SET studentIsRead = 1 
          WHERE leave_id IN (
              SELECT leave_id 
              FROM leave_application 
              WHERE student_id = '$studentId'
          ) 
          AND process = 1 
          AND studentIsRead = 0";
$result = mysqli_query($con, $query);

if ($result) {
    echo "Success";
} else {
    echo "Error";
}
?>
