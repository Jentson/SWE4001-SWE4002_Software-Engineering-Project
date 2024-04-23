<?php
// Function to send notification email to lecturer
function sendNotificationToLecturer($lecturerEmail, $leaveId) {
    // Email configuration
    $to = $lecturerEmail;
    $subject = "Leave Application Notification";
    $message = "Dear Lecturer,\n\n";
    $message .= "A student has applied for leave with the following leave ID: $leaveId.\n\n";
    $message .= "Please take appropriate action as necessary.\n\n";
    $message .= "Sincerely,\nYour Institution";
    $headers = "From: sender@example.com\r\n";
    $headers .= "Reply-To: sender@example.com\r\n";
    
    // Send email
    if (mail($to, $subject, $message, $headers)) {
        return true; // Notification sent successfully
    } else {
        return false; // Failed to send notification
    }
}

// Example usage: Call this function after leave application submission
// Assuming $lecturerEmail and $leaveId are available

// Example:
// sendNotificationToLecturer('lecturer@example.com', 1234);

?>
