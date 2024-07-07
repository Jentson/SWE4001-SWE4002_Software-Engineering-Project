<?php
session_start();
include_once '../database/db.php';
include_once 'mail_config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['time_id']) && isset($_GET['makeReason']) && isset($_GET['student_modal'])) {
    $time_id = $_GET['time_id'];
    $makeReason = $_GET['makeReason'];
    $student_modal = $_GET['student_modal'];

    // Sanitize the time_id parameter to prevent SQL injection
    $time_id = mysqli_real_escape_string($con, $time_id);

    // Check if the selected timetable is still available
    $result = mysqli_query($con, "SELECT ts.*, st.* FROM staff_timeschedule ts JOIN staff st ON ts.staff_id = st.staff_id WHERE ts.time_id = '$time_id' AND ts.book_avail = 'available'");
    $row = mysqli_fetch_array($result);


    if ($row) {
        // Update book_avail status to 'booked'
        $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'booked' WHERE time_id = '$time_id'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Get student ID and name from session
            $student_id = $_SESSION['student_id'];
            $student_name = $_SESSION['student_name'];
            $schedule_date = $row['schedule_date'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $modal = $row['modal'];
            $staff_id = $row['staff_id'];
            $staff_name = $row['staff_name'];
            $staff_email = $row['staff_email'];


            // Record the booking in the appointment_history table
            $insertQuery = "INSERT INTO appointment_history (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason)
                             VALUES ('$time_id', '$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$student_modal', 'booked', '$staff_id', '$makeReason')";
            $insertResult = mysqli_query($con, $insertQuery);

            // Insert appointment details into student_bookings table
            $bookingQuery = "INSERT INTO student_bookings (student_id, staff_id, schedule_date, start_time, end_time, modal, status, reason, booking_time, time_id) 
                            VALUES ('$student_id', '{$row['staff_id']}', '$schedule_date', '$start_time', '$end_time', '$student_modal', 'booked', '$makeReason', NOW(), '$time_id')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertResult) {
                //Get an initialized mailer instance
                $mail = initializeMailer();

                // Add recipient and set email content
                $mail->addAddress($staff_email, $staff_name);
                $mail->Subject = 'Appointment Booking Notification';
                $mail->Body = " <p><strong>Appointment Booking Notification</strong></p>
                                <p>Dear $staff_name,</p>
                                <p>Student <strong>$student_name</strong> has booked a consultation time.</p>
                                <p><strong>Details:</strong></p>
                                <ul>
                                    <li><strong>Date:</strong> $schedule_date</li>
                                    <li><strong>Start Time:</strong> $start_time</li>
                                    <li><strong>End Time:</strong> $end_time</li>
                                    <li><strong>Modal:</strong> $student_modal</li>
                                    <li><strong>Reason:</strong> $makeReason</li>
                                </ul>
                                <p>Thank you.</p>
                                <p>INTI International College Subang Consultation System</p>
                            ";
                
                // Send Message
                $mail->send();
                echo "<script>alert('Appointment made successfully');</script>";
                echo '<script>window.location.href = "../consultation/student_view_cancel_appointment.php";</script>';
            } else {
                echo "Failed to make appointment. Please try again.";
            }
        } else {
            echo "Failed to make appointment. Please try again.";
        }
    } else {
        echo "Selected timetable is no longer available.";
    }
}else if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['time_id']) && isset($_GET['makeReason'])) {
    $time_id = $_GET['time_id'];
    $makeReason = $_GET['makeReason'];

    // Sanitize the time_id parameter to prevent SQL injection
    $time_id = mysqli_real_escape_string($con, $time_id);

    // Check if the selected timetable is still available
    $result = mysqli_query($con, "SELECT ts.*, st.* FROM staff_timeschedule ts JOIN staff st ON ts.staff_id = st.staff_id WHERE ts.time_id = '$time_id' AND ts.book_avail = 'available'");
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Update book_avail status to 'booked'
        $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'booked' WHERE time_id = '$time_id'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Get student ID and name from session
            $student_id = $_SESSION['student_id'];
            $student_name = $_SESSION['student_name'];
            $schedule_date = $row['schedule_date'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $modal = $row['modal'];
            $staff_id = $row['staff_id'];
            $staff_name = $row['staff_name'];
            $staff_email = $row['staff_email'];

            // Record the booking in the student_bookings table
            $insertQuery = "INSERT INTO appointment_history (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason)
                             VALUES ('$time_id', '$student_id', '$student_name', '$schedule_date', '$start_time', '$end_time', '$modal', 'booked', '$staff_id', '$makeReason')";
            $insertResult = mysqli_query($con, $insertQuery);
            

            // Insert appointment details into appointment_history table
            $bookingQuery = "INSERT INTO student_bookings (student_id, staff_id, schedule_date, start_time, end_time, modal, status, reason, booking_time, time_id ) 
                            VALUES ('$student_id', '{$row['staff_id']}', '$schedule_date', '$start_time', '$end_time', '$modal', 'booked', '$makeReason' , NOW(), '$time_id')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertResult) {
                //Get an initialized mailer instance
                $mail = initializeMailer();

                // Add recipient and set email content
                $mail->addAddress($staff_email, $staff_name);
                $mail->Subject = 'Appointment Booking Notification';
                $mail->Body =  " <p><strong>Appointment Booking Notification</strong></p>
                                <p>Dear $staff_name,</p>
                                <p>Student <strong>$student_name</strong> has booked a consultation time.</p>
                                <p><strong>Details:</strong></p>
                                <ul>
                                    <li><strong>Date:</strong> $schedule_date</li>
                                    <li><strong>Start Time:</strong> $start_time</li>
                                    <li><strong>End Time:</strong> $end_time</li>
                                    <li><strong>Modal:</strong> $modal</li>
                                    <li><strong>Reason:</strong> $makeReason</li>
                                </ul>
                                <p>Thank you.</p>
                                <p>INTI International College Subang Consultation System</p>
                            ";
                
                // Send Message
                $mail->send();
                echo "<script>alert('Appointment made successfully');</script>";
                echo '<script>window.location.href = "../consultation/student_view_cancel_appointment.php";</script>';
                } else {
                echo "Failed to make appointment. Please try again.";
            }
        } else {
            echo "Failed to make appointment. Please try again.";
        }
    } else {
        echo "Selected timetable is no longer available.";
    }
}else{
    echo "Failed to make appointment, Please contact Admin.";
}
?>
