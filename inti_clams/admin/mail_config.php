<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Create a function to initialize and return a configured PHPMailer instance
function initializeMailer() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'milomacchiato31@gmail.com';
    $mail->Password   = 'tcxmkamzzpbcmljf';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('milomacchiato31@gmail.com', 'Admin IICS-CLAMS_Notification');
    $mail->isHTML(true);
    return $mail;
}
?>
