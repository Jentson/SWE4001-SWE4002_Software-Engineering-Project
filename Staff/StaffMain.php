<?php

require_once "../db.php";

session_start();

if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
    exit();
}

$staff_id = $_SESSION['Staff_id'];

// Prepare and execute the SQL query
$sql = "SELECT * FROM Staff WHERE staff_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $staff_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $role = $row['roles'];

    // Use the role for further processing or validation
    if ($role == "Lecturer") {
        header("Location: lecturer_dashboard.php");
        exit();
    } elseif ($role == "HOP") {
        header("Location: hop_dashboard.php");
        exit();
    } elseif ($role == "Admin"){
        header("Location: ../Admin/adminMain.php");
        exit();
    }
    else {
        echo '<script>alert("You have no access")</script>';
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
