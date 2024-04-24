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
$student_state = $studentInfo['state'];

ob_start();

if (isset($_POST['Submit'])) {
    
    $assignment_successful = true;

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
    //File upload process
        $pdfFile = $_FILES['files'];
        $fileName = $_FILES['files']['name'];
        $fileTmpName = $_FILES['files']['tmp_name'];
        $fileType = mime_content_type($fileTmpName);

    // Allowed file types
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

        if (in_array($fileType, $allowedTypes)) {
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $fileDestination = '../file/' . uniqid('', true) . '.' . $fileExtension; // Unique filename with extension

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                echo '<script>alert("File uploaded successfully!")</script>';
                echo '<script>window.location.href = "LeaveApplication.php";</script>';
            } else {
                error_log("Error moving file to destination.");
                exit();
            }
        } else {
            echo '<script>alert("Only PDF, JPEG, or PNG files are allowed. Please refile.")</script>';
            echo '<script>window.location.href = "LeaveApplication.php";</script>';
            exit();
        }
        
        // Insert the data into the database for each added subject
        foreach ($addedSubjects as $subject) {
            $query = "INSERT INTO leave_application (
                stud_id, 
                stud_name, 
                subj_code, 
                startDate, 
                endDate, 
                documents, 
                reason, 
                lecturer_approval_status, 
                ioav_approval,
                hop_approval
            ) VALUES (
                '$student_id', 
                '$student_name', 
                '" . mysqli_real_escape_string($conn, $subject) . "', 
                '$startLeave', 
                '$endLeave', 
                '$fileDestination', 
                '" . mysqli_real_escape_string($conn, $reason) . "', 
                'Pending', 
                'Pending', 
                'Pending'
            )";                       
            $result = mysqli_query($conn, $query);

            if ($result) {
                $leave_id = mysqli_insert_id($conn);
                $lecturer_id_query = "SELECT staff_id FROM subject WHERE subj_code = '" . mysqli_real_escape_string($conn, $subject) . "'";
                $lecturer_id_result = mysqli_query($conn, $lecturer_id_query);
            if ($lecturer_id_result) {
                    $lecturer_id_row = mysqli_fetch_assoc($lecturer_id_result);
                    $lecturer_id = $lecturer_id_row['staff_id'];
                    // Insert record into lecturer_approval table
                    $request_query = "INSERT INTO lecturer_approval (leave_id, lecturer_id, status) 
                    VALUES ('$leave_id', '$lecturer_id', 'Pending')";
                    $approval_result = mysqli_query($conn, $request_query);

                    if ($student_state == "International"){
                    //Insert into IOAV_table
                    $hop_application = "INSERT INTO IOAV_approval (leave_id, IOAV_id, process)
                    VALUES ('$leave_id', '123463', 'Pending')";
                    $hop_result = mysqli_query($conn, $hop_application);
                    } elseif ($student_state == "Local") {
                        $nonInternational = "UPDATE leave_application SET ioav_approval = 'Not Required' WHERE id = '$leave_id'";
                        $result = mysqli_query($conn, $nonInternational);
                        if (!$result) {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    } else {
                        echo "";
                    }
                    
                    //Insert into HOP_table
                    $hop_application = "INSERT INTO hop_approval (leave_id, hop_id, process)
                    VALUES ('$leave_id', '123460', 'Pending')";
                    $hop_result = mysqli_query($conn, $hop_application);

                    if (!$hop_result) {
                        $assignment_successful = false;
                        error_log("Error inserting record into hop_approval table" . mysqli_error($conn));
                    }
                    
                    if (!$approval_result) {
                        $assignment_successful = false;
                        error_log("Error inserting record into lecturer_approval table for subject " . $subject . ": " . mysqli_error($conn));
                    }
                } else {
                    $assignment_successful = false;
                    error_log("Error retrieving lecturer ID for subject " . $subject . ": " . mysqli_error($conn));
                }
            } else {
                $assignment_successful = false;
                error_log("Error inserting leave application for subject " . $subject . ": " . mysqli_error($conn));
            }

        }
    } else {
        
    $assignment_successful = false;
        error_log("No subjects were added.");
    }

    if ($assignment_successful) {
        ob_end_clean();
        echo '<script>alert("Leave application submitted")</script>';
        echo '<script>window.location.href = "StudentMain.php";</script>';
        exit();
    } else {
        echo '<script>alert("No subject added/selected")</script>';
        echo '<script>window.location.href = "LeaveApplication.php";</script>';
    }
}
?>
