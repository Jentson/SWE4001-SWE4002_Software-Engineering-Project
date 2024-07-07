<?php
session_start();
include_once '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['time_id']) && isset($_POST['booking_id'])) {
    $time_id = $_GET['time_id'];
    $booking_id = $_POST['booking_id'];


    // Check if the selected timetable is booked
    $result = mysqli_query($con, "SELECT ts.*, st.*
                                  FROM staff_timeschedule ts 
                                  JOIN staff st ON ts.staff_id = st.staff_id 
                                  JOIN student_bookings sb ON ts.time_id = sb.time_id 
                                  WHERE ts.time_id = '$time_id' AND ts.book_avail = 'booked'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Update bookAvail status to 'completed'
        $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'completed' WHERE time_id = '$time_id'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Insert appointment details into appointment_history table
            $insertQuery = "INSERT INTO appointment_history (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason, booking_timestamp)
                             SELECT sb.time_id, sb.student_id, s.student_name, sb.schedule_date, sb.start_time, sb.end_time, sb.modal, 'completed', sb.staff_id, '-' , NOW()
                             FROM student_bookings sb
                             JOIN student s ON sb.student_id = s.student_id
                             WHERE sb.time_id = '$time_id'";
            $insertResult = mysqli_query($con, $insertQuery);

            // Update status in student_bookings table
            $updateBookingQuery = "UPDATE student_bookings SET status = 'completed' WHERE booking_id = '$booking_id'";
            $updateBookingResult = mysqli_query($con, $updateBookingQuery);

            if ($insertResult && $updateBookingResult) {
                echo "<script>alert('Appointment completed successfully'); </script>";
                echo '<script> window.location.href = "../consultation/staff_cancel_student_record.php" ;</script>';
            } else {
                echo "<script>alert('Failed to complete appointment. Please try again.'); </script>";
            }
        } else {
            echo "<script>alert('Selected timetable is not booked or does not exist.'); </script>";
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
