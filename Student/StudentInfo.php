<?php
// Function to retrieve student information based on student ID
function getStudentInfo($conn, $student_id) {
    // Query to retrieve student information
    $query = mysqli_query($conn, "SELECT * FROM Students WHERE stud_id = '$student_id'");
    $result = mysqli_fetch_assoc($query);
    
    // Return student information
    return $result;
}
?>
