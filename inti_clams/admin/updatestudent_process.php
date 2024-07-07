<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../staff/LoginForStaff.php";</script>';
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are provided
    if (isset($_POST['student_id']) && isset($_POST['student_name']) && isset($_POST['student_email']) && 
        isset($_POST['student_phone_number']) && isset($_POST['student_identify_number']) && 
        isset($_POST['student_address']) && isset($_POST['state']) && 
        isset($_POST['department_id']) && isset($_POST['program_id']) && 
        isset($_POST['status_id']) && isset($_POST['gender'])) {
        
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $student_email = $_POST['student_email'];
        $student_phone_number = $_POST['student_phone_number'];
        $student_identify_number = $_POST['student_identify_number'];
        $student_address = $_POST['student_address'];
        $state = $_POST['state'];
        $department_id = $_POST['department_id'];
        $program_id = $_POST['program_id'];
        $status_id = $_POST['status_id'];
        $gender = $_POST['gender'];

        // Prepare the SQL statement to update the student
        $query = "UPDATE student 
                  SET student_name = ?, student_email = ?, student_phone_number = ?, student_identify_number = ?, 
                      student_address = ?, state = ?, department_id = ?, program_id = ?, status_id = ?, gender = ?
                  WHERE student_id = ?";
        $stmt = mysqli_prepare($con, $query);

        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssssisss", $student_name, $student_email, $student_phone_number, $student_identify_number, 
                               $student_address, $state, $department_id, $program_id, $status_id, $gender, $student_id);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Student updated successfully.";
            header("Location: viewstudents.php?update=success");
        } else {
            echo "Error updating student: " . mysqli_error($con);

        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "All fields are required.";
    }

    // Close the database connection
    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
