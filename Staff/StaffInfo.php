<?php
// Function to retrieve student information based on student ID
function getStaffInfo($conn, $staff_id) {
    // Query to retrieve student information
    $query = mysqli_query($conn, "SELECT * FROM Staff WHERE staff_id = '$staff_id'");
    $result = mysqli_fetch_assoc($query);
    
    // Return student information
    return $result;
}
?>
