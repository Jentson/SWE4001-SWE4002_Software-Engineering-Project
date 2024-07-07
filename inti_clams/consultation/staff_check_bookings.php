<?php
session_start();
include_once '../database/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['staff_id'])) {
    exit; // or redirect to login page
}

// Fetch staff ID from session
$staff_id = $_SESSION['staff_id'];

// Query the database to check for new unread bookings for the current staff
$query = "SELECT * FROM student_bookings WHERE staff_id = '$staff_id' AND isRead = 0";
$result = mysqli_query($con, $query);

// Check if there are new unread bookings
if (mysqli_num_rows($result) > 0) {
    // Fetch details of the new unread booking (you can customize this part as needed)
    $booking = mysqli_fetch_assoc($result);
    $bookingDetails = "Date: " . $booking['scheduleDate'] . ", Time: " . $booking['startTime'] . " - " . $booking['endTime'];

    // Output the booking details
    echo $bookingDetails;
} else {
    // No new unread bookings
    echo '';
}
?>
