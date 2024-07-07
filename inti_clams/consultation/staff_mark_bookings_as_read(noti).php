<?php
session_start();
include_once '../database/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['Staff_id'])) {
    exit; // or redirect to login page
}

// Get the logged-in staff ID
$staff_id = $_SESSION['Staff_id'];

// Update the isRead column in the student_bookings table for the logged-in staff member
$query = "UPDATE student_bookings SET isRead = 1 WHERE staff_id = '$staff_id'";
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
