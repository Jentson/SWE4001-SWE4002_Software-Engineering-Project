<?php
require_once "../database/db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['staff_id'], $_POST['staff_name'], $_POST['staff_email'], $_POST['staff_identity_number'], $_POST['staff_address'], $_POST['phone_number'], $_POST['position'], $_POST['department'], $_POST['gender'])) {
        // Sanitize input data
        $staff_id = mysqli_real_escape_string($con, $_POST['staff_id']);
        $staff_name = mysqli_real_escape_string($con, $_POST['staff_name']);
        $staff_email = mysqli_real_escape_string($con, $_POST['staff_email']);
        $staff_identity_number = mysqli_real_escape_string($con, $_POST['staff_identity_number']);
        $staff_address = mysqli_real_escape_string($con, $_POST['staff_address']);
        $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
        $position = mysqli_real_escape_string($con, $_POST['position']);
        $department = mysqli_real_escape_string($con, $_POST['department']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);

        // Update staff data in the database
        $query = "UPDATE staff SET 
                    staff_name = '$staff_name', 
                    staff_email = '$staff_email', 
                    staff_identity_number = '$staff_identity_number', 
                    staff_address = '$staff_address', 
                    phone_number = '$phone_number', 
                    position_id = '$position', 
                    department_id = '$department', 
                    gender = '$gender' 
                  WHERE staff_id = $staff_id";

        if (mysqli_query($con, $query)) {
            // Redirect to displaystaff.php after successful update
            echo '<script>alert("Successfuly updated staff");</script>';
            header("Location: viewstaff.php?update=success");
 
            exit();
        } else {
            echo "Error updating staff: " . mysqli_error($con);
        }
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request.";
}
?>