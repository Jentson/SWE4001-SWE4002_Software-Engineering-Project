<?php
session_start();
include_once '../database/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    exit; // or redirect to login page
}
$student_id = $_SESSION['student_id'];

// Update the isRead column in the student_bookings table
$query = "UPDATE cancel_stafftime SET isRead = 1 WHERE student_id = '$student_id'";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Send a success response
    echo "Bookings marked as read successfully";
} else {
    // Send an error response
    echo "Failed to mark bookings as read";
}
?>
