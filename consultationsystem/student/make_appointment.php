<?php
session_start();
include_once '../database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['timeID']) && isset($_GET['makeReason'])) {
    $timeID = $_GET['timeID'];
    $makeReason = $_GET['makeReason'];

    // Sanitize the timeID parameter to prevent SQL injection
    $timeID = mysqli_real_escape_string($con, $timeID);

    // Check if the selected timetable is still available
    $result = mysqli_query($con, "SELECT * FROM stafftimeschedule WHERE timeID = '$timeID' AND bookAvail = 'available'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Update bookAvail status to 'booked'
        $updateQuery = "UPDATE stafftimeschedule SET bookAvail = 'booked' WHERE timeID = '$timeID'";
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

            // // Record the booking in the appointment_history table
            // $insertQuery = "INSERT INTO appointment_history (student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason)
            //                  VALUES ('$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'booked', '$staff_id', '$makeReason')";
            // $insertResult = mysqli_query($con, $insertQuery);
            // Record the booking in the student_bookings table
            $insertQuery = "INSERT INTO appointment_history (student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, cancel_reason)
                             VALUES ('$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'booked', '$staff_id', '$makeReason')";
            $insertResult = mysqli_query($con, $insertQuery);
            

            // Insert appointment details into appointment_history table
            $bookingQuery = "INSERT INTO student_bookings (student_id, staff_id, booking_time, schedule_date, start_time, end_time, modal, status) VALUES ('$student_id', '{$row['staffID']}', NOW(), '$schedule_date', '$start_time', '$end_time', '$modal', 'booked')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertResult) {
                echo "Appointment made successfully";
                echo '<br><br><a href="../student/student_dashboard.php" class="btn btn-primary">Go Back to Dashboard</a>';
            } else {
                echo "Failed to make appointment. Please try again.";
            }
        } else {
            echo "Failed to make appointment. Please try again.";
        }
    } else {
        echo "Selected timetable is no longer available.";
    }
}
?>
