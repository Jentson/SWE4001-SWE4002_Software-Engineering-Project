<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../staff/LoginForStaff.php";</script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $student_subject_id = $_POST['student_subject_id'];
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $session = $_POST['session'];
    $semester = $_POST['semester'];
    $section_id = $_POST['section_id'];

    // Prepare the SQL statement to update the student's subject details
    $query = "UPDATE student_subjects
              SET student_id = ?, subject_id = ?, session = ?, semester = ?, section_id = ?
              WHERE student_subject_id = ?";
    $stmt = mysqli_prepare($con, $query);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, 'sssssi', $student_id, $subject_id, $session, $semester, $section_id, $student_subject_id);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Student subject updated successfully.";
        header("Location: viewStudent_Subject.php");
    } else {
        echo "Error updating student subject: " . mysqli_error($con);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
