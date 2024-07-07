<?php
session_start();
include '../database/db.php'; // Ensure database connection file is included
include '../staff/staffInfo.php';

// Get staff information
$staffResult = getStaffInfo($con, $_SESSION['Staff_id']);

// Debugging: Output the $staffResult array to see its contents
var_dump($staffResult);

// Assuming the structure is verified, extract the correct key
$staff_id = $staffResult['staff_id']; // Adjust the key name based on the debug output

// Update leave_application notifications as read
$update_query_leave = "
    UPDATE hop_approval
    SET staffIsRead = 1
    WHERE hop_id = '$staff_id' AND staffIsRead = 0 AND process = 0
";
mysqli_query($con, $update_query_leave);

echo "Success";
?>
