<?php
require_once "../database/db.php";

// Function to retrieve staff information based on staff ID
function getStaffInfo($con, $staff_id) {
    // Query to retrieve staff information
    $query = mysqli_query($con, "SELECT * FROM staff
    WHERE staff_id = '$staff_id'");
    $result = mysqli_fetch_assoc($query);
    
    // Return staff information
    return $result;
}
?>
