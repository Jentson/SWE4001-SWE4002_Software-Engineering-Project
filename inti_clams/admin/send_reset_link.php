<?php
require_once 'db_functions.php';
include_once 'mail_config.php';

$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['frozen_accounts'])) {
    $frozen_accounts = unserialize(base64_decode($_POST['frozen_accounts']));

    foreach ($frozen_accounts as $student_id) {
        $student_id = mysqli_real_escape_string($conn, $student_id);

        $query = "SELECT student_email, student_name FROM student WHERE student_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $email = $row['student_email'];
                $student_name = $row['student_name'];
                $token = bin2hex(random_bytes(32));
                $expiry = date("Y-m-d H:i:s", strtotime('+7 hours'));

                $query = "INSERT INTO password_resets (student_id, token, expiry) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("sss", $student_id, $token, $expiry);
                    if ($stmt->execute()) {
                        $mail = initializeMailer();
                            $mail->addAddress($email, $student_name);
                            $mail->Subject = 'Password Reset Request';
                            $mail->Body = "Hello $student_name,<br><br>Click the link below to reset your password:<br><a href='http://localhost//inti_clams/admin/reset_password.php?token=$token'>Reset Password</a><br><br>If you did not request a password reset, please ignore this email.";

                            $mail->send();
                            echo "<script>alert('Message has been sent successfully to $email.<br>');</script>";
                            echo '<script>window.location.href = "../admin/viewStudents.php";</script>';
                        } else {
                        echo "Error saving token to database for student ID $student_id: " . $stmt->error . "<br>";
                    }
                } else {
                    echo "Error preparing statement for token insertion for student ID $student_id: " . $conn->error . "<br>";
                }
            } else {
                echo "Student not found for student ID $student_id.<br>";
            }
        } else {
            echo "Error preparing statement for fetching student email for student ID $student_id: " . $conn->error . "<br>";
        }
    }
    $conn->close();
    header("location:../admin/manageStudents.php");
}
?>
