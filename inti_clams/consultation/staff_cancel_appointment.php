<?php
include_once "../validation/session.php";
include_once '../database/db.php';
include_once 'mail_config.php';


// Check if POST data is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['time_id']) && isset($_POST['cancelReason'])) {
    $time_id = $_POST['time_id'];
    $cancelReason = $_POST['cancelReason'];

    // Sanitize the time_id parameter to prevent SQL injection
    $time_id = mysqli_real_escape_string($con, $time_id);

    // Combined query to fetch details of the appointment and check if it's booked
    $query = "SELECT sb.*, ss.*, st.*, st.staff_name 
              FROM student_bookings sb 
              JOIN student ss ON sb.student_id = ss.student_id 
              JOIN staff st ON sb.staff_id = st.staff_id 
              JOIN staff_timeschedule ts ON ts.time_id = sb.time_id 
              WHERE sb.time_id = '$time_id' AND ts.book_avail = 'booked'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Update book_avail status to 'cancel_by_staff' in staff_timeschedule table
        $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'cancel_by_staff' WHERE time_id = '$time_id'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Insert cancellation details into cancel_stafftime table
            $schedule_date = $row['schedule_date'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $modal = $row['modal'];
            $student_id = $row['student_id'];
            $student_name = $row['student_name'];
            $student_email = $row['student_email'];
            $staff_name = $row['staff_name'];
            $staff_id = $row['staff_id'];

            $insertQuery = "INSERT INTO cancel_stafftime 
                            (time_id, staff_id, schedule_date, start_time, end_time, modal, status, student_id, reason, cancel_timestamp) 
                            VALUES 
                            ('$time_id', '$staff_id', '$schedule_date', '$start_time', '$end_time', '$modal', 'cancel_by_staff', '$student_id', '$cancelReason', NOW())";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                // Insert appointment details into appointment_history table
                $insertHistoryQuery = "INSERT INTO appointment_history 
                                        (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason, booking_timestamp)
                                        VALUES 
                                        ('$time_id', '$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'cancel_by_staff', '$staff_id', '$cancelReason', NOW())";
                $insertHistoryResult = mysqli_query($con, $insertHistoryQuery);

                if ($insertHistoryResult) {
                    $mail = initializeMailer();
                    // Set sender and recipient
                    $mail->addAddress($student_email, $student_name);
                    // Set email subject and content
                    $mail->Subject = 'Consultation Cancelled Notification';
                    $mail->Body = "
                    <p><strong>Consultation Appointment Cancellation Notification</strong></p>
                    <p>Dear $student_name,</p>
                    <p>Staff <strong>$staff_name</strong> has <strong>cancelled</strong> an appointment.</p>
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
                    echo "<script>alert ('Appointment canceled and history updated successfully'); </script>";
                    echo '<script> window.location.href = "../consultation/staff_cancel_student_record.php" ;</script>';

                } else {
                    echo "Failed to update appointment history. Please try again. Error: " . mysqli_error($con);
                }
            } else {
                echo "Failed to cancel appointment. Please try again. Error: " . mysqli_error($con);
            }
        } else {
            echo "Failed to update booking availability. Please try again.";
        }
    } else {
        echo "Selected appointment not found or already canceled.";
    }
} else {
    echo "Invalid request.";
}

?>
