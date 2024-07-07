<?php
session_start();
include_once '../database/db.php';
include_once 'mail_config.php';


$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['time_id'])) {
    $time_id = $_GET['time_id'];
    $cancelReason = $_POST['cancelReason'];


    // Check if the selected timetable is booked
    $result = mysqli_query($con, "SELECT ts.*, st.*, sb.modal FROM staff_timeschedule ts 
                                  JOIN staff st ON ts.staff_id = st.staff_id 
                                  JOIN student_bookings sb ON ts.time_id = sb.time_id 
                                  WHERE ts.time_id = '$time_id' 
                                  AND ts.book_avail = 'booked' 
                                  AND sb.student_id = '$student_id' 
                                  ORDER BY sb.booking_id DESC LIMIT 1");
     $row = mysqli_fetch_array($result);

    if ($row) {
        // Update bookAvail status to 'available'
        $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'available' WHERE time_id = '$time_id'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Get student ID and name from session
            $schedule_date = $row['schedule_date'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $modal = $row['modal'];
            $staff_id = $row['staff_id'];
            $staff_name = $row['staff_name'];
            $staff_email = $row['staff_email'];


            // Insert appointment details into appointment_history table
            $insertQuery = "INSERT INTO appointment_history (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason)
                             VALUES ('$time_id', '$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'cancelled', '$staff_id', '$cancelReason')";
            $insertResult = mysqli_query($con, $insertQuery);

            // Record the booking in the student_bookings table
            $bookingQuery = "INSERT INTO student_bookings (student_id, staff_id, schedule_date, start_time, end_time, modal, status, reason, booking_time , time_id) 
                            VALUES ('$student_id', '{$row['staff_id']}', '$schedule_date', '$start_time', '$end_time', '$modal', 'cancelled' , '$cancelReason',  NOW(), '$time_id')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertResult) {
            //Get an initialized mailer instance
            $mail = initializeMailer();

            // Add recipient and set email content
            $mail->addAddress($staff_email, $staff_name);
            $mail->Subject = 'Consultation Appointment Cancelled Notification';
            $mail->Body = "
            <p><strong>Consultation Appointment Cancellation Notification</strong></p>
            <p>Dear $staff_name,</p>
            <p>Student <strong>$student_name</strong> has <strong>cancelled</strong> an appointment.</p>
            <p><strong>Details:</strong></p>
            <ul>
                <li><strong>Date:</strong> $schedule_date</li>
                <li><strong>Start Time:</strong> $start_time</li>
                <li><strong>End Time:</strong> $end_time</li>
                <li><strong>Modal:</strong> $modal</li>
                <li><strong>Reason:</strong> $cancelReason</li>
            </ul>
            <p>Thank you.</p>
            <p>INTI International College Subang Consultation System</p>
        ";
        
            // Try to send the email
            $mail->send();
            echo "<script>alert('Appointment cancelled successfully');</script>";
            echo '<script>window.location.href = "../consultation/student_view_cancel_appointment.php";</script>';
        } else {
            echo "Failed to cancel appointment. Please try again.";
        }
    } else {
        echo "Selected timetable is not booked or does not exist.";
    }
}
}
?>