<?php
require_once "../database/db.php";

session_start();

if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../intizh/login.php";</script>';
    session_destroy();
    exit();
}

if (isset($_POST["submit"])) {
    if ($_FILES['file']['name']) {
        $filename = explode(".", $_FILES['file']['name']);
        if (end($filename) == "csv") {
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            while ($data = fgetcsv($handle)) {
                $student_id = mysqli_real_escape_string($con, $data[1]);
                $subject_id = mysqli_real_escape_string($con, $data[3]);
                $program_id = mysqli_real_escape_string($con, $data[5]);
                $session = mysqli_real_escape_string($con, $data[7]);
                $semester = mysqli_real_escape_string($con, $data[8]);
                $section_id = mysqli_real_escape_string($con, $data[9]);

                // Check if student_id exists in the student table
                $check_student_query = "SELECT student_id FROM student WHERE student_id = '$student_id'";
                $student_result = mysqli_query($con, $check_student_query);

                if (mysqli_num_rows($student_result) == 0) {
                    // Student ID does not exist, log or skip this entry
                    error_log("Student ID $student_id does not exist. Skipping this entry.");
                    continue;
                }

                // Check if program_id exists in the program table
                $check_program_query = "SELECT program_id FROM program WHERE program_id = '$program_id'";
                $program_result = mysqli_query($con, $check_program_query);

                if (mysqli_num_rows($program_result) == 0) {
                    // Program ID does not exist, log or skip this entry
                    error_log("Program ID $program_id does not exist. Skipping this entry.");
                    continue;
                }

                // Check if the record exists
                $query = "SELECT * FROM student_subjects WHERE student_id = '$student_id' AND subject_id = '$subject_id' AND program_id = '$program_id'";
                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    // Record exists, update it
                    $update_query = "UPDATE student_subjects SET session = '$session', semester = '$semester', section_id = '$section_id' WHERE student_id = '$student_id' AND subject_id = '$subject_id' AND program_id = '$program_id'";
                    mysqli_query($con, $update_query);
                } else {
                    // Record does not exist, insert it
                    $insert_query = "INSERT INTO student_subjects (student_id, subject_id, program_id, session, semester, section_id) VALUES ('$student_id', '$subject_id', '$program_id', '$session', '$semester', '$section_id')";
                    mysqli_query($con, $insert_query);
                }
            }
            fclose($handle);
            echo '<script>alert("CSV File has been successfully imported.");</script>';
            echo '<script>window.location.href = "adminmain.php";</script>';
        } else {
            echo '<script>alert("Please upload a valid CSV file.");</script>';
            echo '<script>window.location.href = "uploadStudent_Subject.php";</script>';
        }
    } else {
        echo '<script>alert("Please select a file to upload.");</script>';
        echo '<script>window.location.href = "uploadStudent_Subject.php";</script>';
    }
}
?>
