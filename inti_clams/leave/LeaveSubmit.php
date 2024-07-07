<?php

require_once "../database/db.php";
require_once "../validation/sanitizeInput.php";
require "mail_config.php";
require "log_actions.php";
include '../validation/session.php'; 

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$studentId = $_SESSION['student_id'];

$studentInfo = mysqli_query($con, 
"SELECT *, student.*, student.student_phone_number AS phone, student.student_address AS address
FROM student 
JOIN program ON program.program_id = student.program_id
LEFT JOIN student_subjects ON student_subjects.student_id = student.student_id
WHERE student.student_id = '$studentId'");

$studentResult = mysqli_fetch_assoc($studentInfo);

$student_name = $studentResult['student_name'];
$student_state = $studentResult['state'];
$student_department = $studentResult['department_id'];

ob_start();

if (isset($_POST['Submit'])) {
    
    $assignment_successful = true;
    
    $limit = 10; // Limit actions 
    $interval = 30; // Interval in minutes

        if (!canPerformAction($con, $studentId, 'apply', $limit, $interval)) {
            // If rate limit exceeded, display an error message
            echo '<script>alert("You have exceeded the rate limit for applying leave. Please try again later.")</script>';
            echo '<script>window.location.href = "StudentMain.php";</script>';
            exit();
        }

    // Retrieve form data
    $startLeave = $_POST['startDate'];
    $endLeave = $_POST['endDate'];
    $reason = sanitizeInput($_POST['inputDescription']);

    $addedSubjects = [];

    if(isset($_POST['enrolled_subjects'])) {
        // Retrieve the array of checked checkboxes
        $addedSubjects = $_POST['enrolled_subjects'];
    } else {
        error_log("No subject selected"); 
        $assignment_successful = false;
    }

if (!empty($addedSubjects)) {
    // Check for duplicate leave applications
    $duplicateSubjects = [];
        foreach ($addedSubjects as $subject) {
            $checkDuplicateQuery = "SELECT COUNT(*) as count FROM leave_application
                                        WHERE student_id = '$studentId'
                                        AND subject_id = '" . mysqli_real_escape_string($con, $subject) . "'
                                        AND start_date = '$startLeave'
                                        AND end_date = '$endLeave'";
            $checkDuplicateResult = mysqli_query($con, $checkDuplicateQuery);
            $row = mysqli_fetch_assoc($checkDuplicateResult);
    
        if ($row['count'] > 0) {
                $duplicateSubjects[] = $subject;
            }
        }

        if (!empty($duplicateSubjects)) {
            // Handle duplication error
            $duplicateSubjectsList = implode(", ", $duplicateSubjects);
            echo 'Duplicate';
            echo '<script>alert("Duplicate leave application found for subjects: ' . $duplicateSubjectsList . '");</script>';
            echo '<script>window.location.href = "LeaveApplication.php";</script>';
            exit(); // Exit the script to prevent further execution
        }
        
    // Check if a file was uploaded
    if (!empty($_FILES['files']['name'])) {
        $pdfFile = $_FILES['files'];
        $fileName = $_FILES['files']['name'];
        $fileTmpName = $_FILES['files']['tmp_name'];
        $fileType = mime_content_type($fileTmpName);
    
        // Allowed file types
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    
        if (in_array($fileType, $allowedTypes)) {
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $uniqueIdentifier = uniqid(); // Generate unique identifier
            $newFileName = $fileName . '_' . $uniqueIdentifier . '.' . $fileExtension; // Append unique identifier to original file name
            $fileDestination = '../leave/file/' . $newFileName; // New file destination
    
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // File uploaded successfully
                $fileStatus = "File uploaded successfully!";
                // Insert file destination into database
                $document = $newFileName; // Store the file name to save in the database
            } else {
                error_log("Error moving file to destination.");
                exit();
            }
        } else {
            // Invalid file type
            $fileStatus = "Only PDF, JPEG, or PNG files are allowed. Please refile.";
            $document = ""; // No valid file uploaded
        }
    } else {
        // No file uploaded
        $document = ""; // Set to empty string if no file uploaded
    }
    
    // Proceed with inserting or updating the database record with $document
    // Example: insert into applications (name, documents) values ($name, $document);
    
        // Insert the data into the database for each added subject
        foreach ($addedSubjects as $subject) {
            $query = "INSERT INTO leave_application (
                student_id, 
                student_name, 
                subject_id, 
                state,
                start_date, 
                end_date, 
                reason, 
                documents, 
                department,
                lecturer_approval, 
                ioav_approval,
                hop_approval
            ) VALUES (
                '$studentId', 
                '$student_name', 
                '" . mysqli_real_escape_string($con, $subject) . "', 
                '$student_state',
                '$startLeave', 
                '$endLeave', 
                '" . mysqli_real_escape_string($con, $reason) . "', 
                '$fileDestination', 
                '$student_department',
                'Pending', 
                'Pending', 
                'Pending'
            )";                       
            $result = mysqli_query($con, $query);

            if ($result) {
                $leave_id = mysqli_insert_id($con);
                $lecturer_id_query = "SELECT * FROM subject JOIN 
                staff ON subject.staff_id = staff.staff_id
                 WHERE subject.subject_id = '" 
                . mysqli_real_escape_string($con, $subject) . "'";
                $lecturer_id_result = mysqli_query($con, $lecturer_id_query);

            if ($lecturer_id_result) {
                    $lecturer_id_row = mysqli_fetch_assoc($lecturer_id_result);
                    $lecturer_id = $lecturer_id_row['staff_id'];
                    $lecturer_name = $lecturer_id_row['staff_name'];
                    $lecturer_email = $lecturer_id_row['staff_email'];

                    // Insert record into lecturer_approval table
                    $request_query = "INSERT INTO lecturer_approval (leave_id, lecturer_id, process) 
                    VALUES ('$leave_id', '$lecturer_id', 'Pending')";
                    $approval_result = mysqli_query($con, $request_query);
                    
                    // Get an initialized mailer instance
                    $mail = initializeMailer();

                  // Add recipient and set email content
                    $mail->addAddress($lecturer_email, $lecturer_name);
                    $mail->Subject = 'Leave Application Notification';

                    // Start with an initial message body
                    $mail->Body = "
                        <p><strong>Leave Application Notification</strong></p>
                        <p>Dear $lecturer_name,</p>
                        <p>Student <strong>$student_name</strong> has applied for leave.</p>
                        <p><strong>Details:</strong></p>
                        <ul>
                            <li><strong>Subject ID:</strong> {$subject}</li>
                            <li><strong>Start Date:</strong> {$startLeave}</li>
                            <li><strong>End Date:</strong> {$endLeave}</li>
                            <li><strong>Reason:</strong> {$reason}</li>
                        </ul>
                        <p>Thank you.</p>
                        <p>INTI International College Subang Leave System</p>
                    ";
                    // Send the email
                    $mail->send();
                    
                    if ($student_state == "Local") {
                        $nonInternational = "UPDATE leave_application SET ioav_approval = 'Not Required' 
                        WHERE leave_id = '$leave_id'";
                        $result = mysqli_query($con, $nonInternational);
                        if (!$result) {
                            echo "Error updating record: " . mysqli_error($con);
                        }
                    } else {
                        echo "Success";
                    }
                    
                    if (!$approval_result) {
                        $assignment_successful = false;
                        error_log("Error inserting record into lecturer_approval table for subject " . $subject . ": " . mysqli_error($con));
                    }
                } 
            } else {
                $assignment_successful = false;
                error_log("Error inserting leave application for subject " . $subject . ": " . mysqli_error($con));
            }

        }
    } else {
        
    $assignment_successful = false;
        error_log("No subjects were added.");
    }

    if ($assignment_successful){
        logAction($con, $studentId, 'apply', $leave_id);
        ob_end_clean();
        echo '<script>alert("Leave application submitted")</script>';
        echo '<script>window.location.href = "../leave/CancelLeave.php";</script>';
        
        exit();
    } else {
        echo '<script>alert("No subject added/selected")</script>';
        echo '<script>window.location.href = "../leave/LeaveApplication.php";</script>';
    }
}
?>
