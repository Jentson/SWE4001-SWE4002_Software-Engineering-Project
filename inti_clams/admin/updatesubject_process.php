<?php
require_once "../database/db.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are provided
    if (isset($_POST['subject_id']) && isset($_POST['subject_name']) && isset($_POST['department_id']) && isset($_POST['staff_id'])) {
        $subject_id = $_POST['subject_id'];
        $subject_name = $_POST['subject_name'];
        $department_id = $_POST['department_id'];
        $staff_id = $_POST['staff_id'];

        // Prepare the SQL statement to update the subject
        $query = "UPDATE subject SET subject_name = ?, department_id = ?, staff_id = ? WHERE subject_id = ?";
        $stmt = mysqli_prepare($con, $query);

        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssss", $subject_name, $department_id, $staff_id, $subject_id);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Subject updated successfully.";
            header("Location: viewsubject.php?update=success");
        } else {
            echo "Error updating subject: " . mysqli_error($con);
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
