<?php
session_start();
require_once "../db.php";
require_once "../sanitizeInput.php";
require_once "../Student/StudentInfo.php";

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

if (!isset($_SESSION['stud_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStudent.html";</script>';
    exit();
}

$student_id = $_SESSION['stud_id'];
$studentInfo = getStudentInfo($conn, $student_id);
$student_name = $studentInfo['stud_name'];
$leave_ref = $student_id;

ob_start();

if (isset($_POST['Submit'])) {
    // File upload
    $pdfFile = $_FILES['files'];
    $fileName = $_FILES['files']['name'];
    $fileTmpName = $_FILES['files']['tmp_name'];
    $fileType = mime_content_type($fileTmpName);

    if ($fileType === 'application/pdf') {
        $destination = '../file/' . uniqid() . '_' . $fileName; // Unique filename
        if (!move_uploaded_file($fileTmpName, $destination)) {
            error_log("Error moving file to destination.");
            exit();
        }
    } else {
        echo '<script>alert("Only PDF files are allowed. Please refile")</script>';
        echo '<script>window.location.href = "LeaveApplication.php";</script>';
        exit();
    }

    // Retrieve form data
    $startLeave = $_POST['startDate'];
    $endLeave = $_POST['endDate'];
    $reason = sanitizeInput($_POST['inputDescription']);
    $addedSubjects = isset($_POST['addedSubjects']) ? json_decode($_POST['addedSubjects'], true) : [];

    $assignment_successful = true;

    if (!empty($addedSubjects)) {
        // Insert the data into the database for each added subject
        foreach ($addedSubjects as $subject) {
            $status = "Pending";
            $query = "INSERT INTO leave_application (leave_ref, stud_id, stud_name, subj_code, startDate, endDate, documents, reason, status) VALUES ('$leave_ref','$student_id', '$student_name', '" . mysqli_real_escape_string($conn, $subject['code']) . "', '$startLeave', '$endLeave', '$destination', '" . mysqli_real_escape_string($conn, $reason) . "', '$status')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $leave_id = mysqli_insert_id($conn);
                $lecturer_id_query = "SELECT lect_id FROM subject WHERE subj_code = '" . mysqli_real_escape_string($conn, $subject['code']) . "'";
                $lecturer_id_result = mysqli_query($conn, $lecturer_id_query);

                if ($lecturer_id_result) {
                    $lecturer_id_row = mysqli_fetch_assoc($lecturer_id_result);
                    $lecturer_id = $lecturer_id_row['lect_id'];
                    $assignment_query = "INSERT INTO leave_application_assignment (leave_application_id, lecturer_id, status) VALUES ('$leave_id', '$lecturer_id', '$status')";
                    $assignment_result = mysqli_query($conn, $assignment_query);

                    if (!$assignment_result) {
                        $assignment_successful = false;
                        error_log("Error assigning leave application for subject " . $subject['code'] . ": " . mysqli_error($conn));
                    }
                } else {
                    $assignment_successful = false;
                    error_log("Error retrieving lecturer ID for subject " . $subject['code'] . ": " . mysqli_error($conn));
                }
            } else {
                $assignment_successful = false;
                error_log("Error inserting leave application for subject " . $subject['code'] . ": " . mysqli_error($conn));
            }
        }
    } else {
        error_log("No subjects were added.");
    }

    if ($assignment_successful) {
        ob_end_clean();
        echo '<script>alert("Leave application submitted")</script>';
        echo '<script>window.location.href = "StudentMain.php";</script>';
        exit();
    } else {
        echo "Error submitting leave applications.";
    }
}

mysqli_close($conn);
?>