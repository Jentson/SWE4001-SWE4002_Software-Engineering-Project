<?php
include_once '../database/dbconnect.php';
// Get the variables.
if(isset($_POST['time_id'])) {
    $time_id = $_POST['time_id'];

    // Delete related records in appointment_history table first
    $query_delete_bookings = "DELETE FROM student_bookings WHERE time_id = '$time_id'";
    $result_delete_bookings = mysqli_query($con, $query_delete_bookings);

    if($result_delete_bookings) {
        // Now delete the record from staff_timeschedule
        $query_delete = "DELETE FROM staff_timeschedule WHERE time_id = '$time_id'";
        $result_delete = mysqli_query($con, $query_delete);

        if($result_delete) {
            // Delete successful, show alert
            echo "<script>alert('Record deleted successfully');</script>";
            echo "<script>window.location.href = 'staff_view_timeschedule.php';</script>";
        } else {
            // Delete failed, show alert
            echo "<script>alert('Error deleting record: " . mysqli_error($con) . "');</script>";
        }
    } else {
        // Delete related records failed, show alert
        echo "<script>alert('Error deleting related records: " . mysqli_error($con) . "');</script>";
    }
} else {
    // Time ID not set, show alert
    echo "<script>alert('Time ID not set.');</script>";
}

?>

