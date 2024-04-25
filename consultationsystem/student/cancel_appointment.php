<?php
session_start();
include_once '../database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['timeID'])) {
    $timeID = $_GET['timeID'];
    $cancelReason = $_POST['cancelReason'];


    // Check if the selected timetable is booked
    $result = mysqli_query($con, "SELECT * FROM stafftimeschedule WHERE timeID = '$timeID' AND bookAvail = 'booked'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Update bookAvail status to 'available'
        $updateQuery = "UPDATE stafftimeschedule SET bookAvail = 'available' WHERE timeID = '$timeID'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Get student ID and name from session
            $student_id = $_SESSION['student_id'];
            $student_name = $_SESSION['student_name'];
            $schedule_date = $row['scheduleDate'];
            $start_time = $row['startTime'];
            $end_time = $row['endTime'];
            $modal = $row['modal'];
            $staff_id = $row['staffID'];

            // Insert appointment details into appointment_history table
            $insertQuery = "INSERT INTO appointment_history (student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, cancel_reason)
                             VALUES ('$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'cancelled', '$staff_id', '$cancelReason')";
            $insertResult = mysqli_query($con, $insertQuery);

            // Record the booking in the student_bookings table
            $bookingQuery = "INSERT INTO student_bookings (student_id, staff_id, booking_time, schedule_date, start_time, end_time, modal, status) VALUES ('$student_id', '{$row['staffID']}', NOW(), '$schedule_date', '$start_time', '$end_time', '$modal', 'cancelled')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertResult) {
            echo "Appointment canceled successfully";
            echo '<br><br><a href="../student/student_dashboard.php" class="btn btn-primary">Go Back to Dashboard</a>';
        } else {
            echo "Failed to cancel appointment. Please try again.";
        }
    } else {
        echo "Selected timetable is not booked or does not exist.";
    }
}
}
?>