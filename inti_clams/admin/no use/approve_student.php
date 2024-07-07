<?php
require_once "../database/dbconnect.php"; // Include your database connection file
require '../vendor/autoload.php'; // Include Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the student ID is provided in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id']; // Get the student ID from the URL
    
    // Fetch the student's email and name from the database
    $query = "SELECT student_email, student_name FROM student WHERE student_id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($student_email, $student_name);
    $stmt->fetch();
    $stmt->close();

    if (empty($student_email) || empty($student_name)) {
        echo "Student data not found.";
        exit();
    }

    // Update the status of the student account to 'Approved' in the database
    $query = "UPDATE student SET status_id = '2' WHERE student_id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    $stmt->bind_param("s", $student_id);

    // Execute the query
    if ($stmt->execute()) {
        echo 'Student status updated successfully.<br>'; // Debugging line

        // Setting up PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 3;  // Enable verbose debug output
            $mail->isSMTP();  // Send using SMTP
            $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
            $mail->SMTPAuth = true;  // Enable SMTP authentication
            $mail->Username = 'marketsean666@gmail.com';  // SMTP username
            $mail->Password = 'vbaslasouihgjryt';  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Enable implicit TLS encryption
            $mail->Port = 465;  // TCP port to connect to

            // Recipients
            $mail->setFrom('marketsean666@gmail.com', 'TestingIntiService');
            $mail->addAddress($student_email, $student_name);

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'INTI Service';
            $mail->Body = '<p>Dear ' . $student_name . ',</p>';
            $mail->Body .= '<p>We are pleased to inform you that your application has been approved.</p>';
            $mail->Body .= '<p>Sincerely,<br>INTIService</p>';

            if ($mail->send()) {
                echo 'Message has been sent<br>';
            } else {
                echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '<br>';
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
        }

        // Redirect back to the page where pending student accounts are displayed
        header("Location: student_approval.php");
        exit();
    } else {
        // If there was an error with the query, display an error message
        echo "Error approving the student account: " . $stmt->error;
    }

    $stmt->close();
} else {
    // If the student ID is not provided in the URL, redirect back to the page where pending student accounts are displayed
    header("Location: student_approval.php");
    exit();
}

$con->close();
?>
