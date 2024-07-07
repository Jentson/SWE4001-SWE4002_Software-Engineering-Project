<?php
require_once "../database/dbconnect.php"; // Include your database connection file

// Check if the student ID is provided in the URL
if(isset($_GET['id'])) {
    $student_id = $_GET['id']; // Get the student ID from the URL
    
    // Update the status of the student account to 'Approved' in the database
    $query = "UPDATE student SET status_id = '2' WHERE student_id = ?";
    $stmt = $con->prepare($query);

    if(!$stmt) {
        // Print SQL syntax error if prepare fails
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit();
    }

    $stmt->bind_param("s", $student_id);

    // Execute the query
    if($stmt->execute()) {
        // If the query was successful, redirect back to the page where pending student accounts are displayed
        header("Location: student_approval.php");
        exit();
    } else {
        // If there was an error with the query, display an error message
        echo "Error approving the student account: " . $stmt->error;
    }
} else {
    // If the student ID is not provided in the URL, redirect back to the page where pending student accounts are displayed
    header("Location: student_approval.php");
    exit();
}

$stmt->close();
$con->close();
?>
