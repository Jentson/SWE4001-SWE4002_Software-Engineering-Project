<?php
require_once 'db_functions.php';
include_once 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = connectDB();

// Check if any approved accounts
if (isset($_POST['approve_accounts'])) {
    $approve_accounts_encoded = $_POST['approve_accounts'];
    $approve_accounts = unserialize(base64_decode($approve_accounts_encoded));
    foreach ($approve_accounts as $student_id) {
        $query = "SELECT student_email, student_name FROM student WHERE student_id = ? AND status_id = 2";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $mail = initializeMailer();
                 // Add recipient and set email content
                $email = $row['student_email'];
                $student_name = $row['student_name'];
                $mail->addAddress($email, $student_name);
                $mail->Subject = 'Account Status Update';
                $message = "Hello $student_name,<br><br>Your account has been approved.<br><br>Thank you.";
                $mail->Body = $message;

                $mail->send();
                echo "<script>alert('Successfully updated student status.');</script>";
                echo '<script>window.location.href = "../admin/viewStudents.php";</script>';
            } else {
                echo "Approved student not found for student ID $student_id.<br>";
            }
        } else {
            echo "Error preparing statement for fetching approved student details for student ID $student_id: " . $conn->error . "<br>";
        }
    }
}

// Check if any rejected accounts
if (isset($_POST['reject_accounts'])) {
    $reject_accounts_encoded = $_POST['reject_accounts'];
    $reject_accounts = unserialize(base64_decode($reject_accounts_encoded));
    foreach ($reject_accounts as $student_id) {
        $query = "SELECT student_email, student_name FROM student WHERE student_id = ? AND status_id = 3";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $mail = initializeMailer();
                // Add recipient and set email content
                $email = $row['student_email'];
                $student_name = $row['student_name'];
                $mail->addAddress($email, $student_name);
                $mail->Subject = 'Account Status Update';
                $message = "Hello $student_name,<br><br>We regret to inform you that your account registration has been rejected.<br><br>Thank you.<br><br>
                Any details please go to office to contact with the admin.";
                $mail->Body = $message;

                $mail->send();
                echo "<script>alert('Successfully rejected the student.');</script>";
                echo '<script>window.location.href = "../admin/viewStudents.php";</script>';
            } else {
                echo "Rejected student not found for student ID $student_id.<br>";
            }
        } else {
            echo "Error preparing statement for fetching rejected student details for student ID $student_id: " . $conn->error . "<br>";
        }
    }
}
?>
