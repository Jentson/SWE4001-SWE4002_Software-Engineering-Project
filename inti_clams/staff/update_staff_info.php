<?php
require_once "../database/db.php";
include '../validation/session.php'; 

$staff_id = $_SESSION['Staff_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, $_POST['address']) : null;
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($con, $_POST['phone']) : null;
    $currentPassword = isset($_POST['current_password']) ? mysqli_real_escape_string($con, $_POST['current_password']) : null;
    $newPassword = isset($_POST['new_password']) ? mysqli_real_escape_string($con, $_POST['new_password']) : null;
    $confirmPassword = isset($_POST['confirm_password']) ? mysqli_real_escape_string($con, $_POST['confirm_password']) : null;

    $updateFields = [];
    $errors = [];

    // Check if current password is provided for password update
    if (!empty($currentPassword)) {
        $query = "SELECT staff_pass FROM staff WHERE staff_id='$staff_id'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);

        if (password_verify($currentPassword, $row['staff_pass'])) {
            // Current password is correct, check new password and confirm password
            if (!empty($newPassword) && $newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateFields[] = "staff_pass='$hashedPassword'";
            } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
                $errors[] = "New password and confirm password do not match!";
            }
        } else {
            $errors[] = "Current password is incorrect!";
        }
    }

    // Update address if provided
    if (!empty($address)) {
        $updateFields[] = "staff_address='$address'";
    }

    // Update phone if provided
    if (!empty($phone)) {
        $updateFields[] = "phone_number='$phone'";
    }

    // If there are no errors, execute the update query
    if (empty($errors) && !empty($updateFields)) {
        $updateQuery = "UPDATE staff SET " . implode(', ', $updateFields) . " WHERE staff_id='$staff_id'";
        if (mysqli_query($con, $updateQuery)) {
            echo '<script>alert("Profile updated successfully!");</script>';
        } else {
            echo '<script>alert("Error updating profile!");</script>';
        }
    } else {
        foreach ($errors as $error) {
            echo '<script>alert("'.$error.'");</script>';
        }
    }

    echo '<script>window.location.href = "StaffProfile.php";</script>';
}
?>
