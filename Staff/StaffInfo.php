<?php
// Function to retrieve staff information based on staff ID
function getStaffInfo($conn, $staff_id) {
    // Query to retrieve staff information
    $query = mysqli_query($conn, "SELECT * FROM Staff WHERE staff_id = '$staff_id'");
    $result = mysqli_fetch_assoc($query);
    
    // Return staff information
    return $result;
}
?>
