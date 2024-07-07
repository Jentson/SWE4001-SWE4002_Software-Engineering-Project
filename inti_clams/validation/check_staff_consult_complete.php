
<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once '../database/db.php';
//Load Composer's autoloader
require '../consultation/vendor/autoload.php';

date_default_timezone_set('Asia/Kuala_Lumpur'); // 设置时区

$current_time = new DateTime();
$threshold_time = clone $current_time;
$threshold_time->modify('-20 minutes');

$query = "
    SELECT ts.*, st.staff_email, s.student_email, s.student_name 
    FROM staff_timeschedule ts 
    JOIN staff st ON ts.staff_id = st.staff_id 
    JOIN student_bookings sb ON ts.time_id = sb.time_id
    JOIN student s ON sb.student_id = s.student_id
    WHERE ts.schedule_date = CURDATE() 
    AND ts.end_time <= ? 
    AND ts.book_avail = 'booked'
";

$threshold_time_str = $threshold_time->format('H:i:s');
$stmt->bind_param('s', $threshold_time_str);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $time_id = $row['time_id'];
    $student_email = $row['student_email'];
    $student_name = $row['student_name'];
    $staff_email = $row['staff_email'];
    $staff_name = $row['staff_name'];
    
    // Update book_avail to 'incomplete'
    $updateQuery = "UPDATE staff_timeschedule SET book_avail = 'incomplete' WHERE time_id = '$time_id'";
    $updateResult = mysqli_query($con, $updateQuery);
    
    if ($updateResult) {
        // Set up PHPMailer

        $mail = new PHPMailer(true);
        
        try {
            $mail->SMTPDebug = 0; // Disable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; // Your SMTP username
            $mail->Password = 'your-password'; // Your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('your-email@gmail.com', 'Notification Service');
            $mail->addAddress($student_email, $student_name);
            $mail->addAddress($staff_email, $staff_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Incomplete Notification';
            $mail->Body = "<b>Dear $student_name and $staff_name,</b><br><br>
                           The appointment scheduled for today was marked as incomplete since the staff member has not clicked the 'Complete' button within 20 minutes after the end time.<br>
                           If the meeting has already been completed, please ignore this message. If not, please contact each other to reschedule the meeting.<br><br>
                           Thank you.";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

$stmt->close();
$con->close();
?>