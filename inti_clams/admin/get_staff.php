<?php
include_once '../database/db.php';

if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];

    // Fetch staff based on the selected department
    $query = "SELECT staff_id, staff_name FROM staff WHERE department_id = $department_id";
    $result = mysqli_query($con, $query);

    // Check if there are any staff members
    if (mysqli_num_rows($result) > 0) {
        // Loop through each staff member and create an option element
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['staff_id'] . "'>" . $row['staff_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No staff found</option>";
    }
}
?>
