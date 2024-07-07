<?php
session_start();

if (!isset($_SESSION['Staff_id']) && !isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folder'])) {
    $folder = $_POST['folder'];

    // Navigate to the specific dashboard based on the folder selected and user type
    if ($folder === 'consultation') {
        if (isset($_SESSION['Staff_id'])) {
            header("Location: ../Staff/StaffMain.php");
        } elseif (isset($_SESSION['student_id'])) {
            header("Location: consultationsystem\student\student_dashboard.php");
        }
    } elseif ($folder === 'leave') {
        if (isset($_SESSION['Staff_id'])) {
            header("Location: leavesystem\Staff\StaffMain.php");
        } elseif (isset($_SESSION['student_id'])) {
            header("Location: leavesystem\Student\StudentMain.php");
        }
    } else {
        echo "Invalid selection.";
    }
} else {
    echo "No folder selected.";
}
?>
