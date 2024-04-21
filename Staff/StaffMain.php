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
        // Redirect to the lecturer dashboard
        header("Location: lecturer_dashboard.php");
        exit();
    } elseif ($role == "IOAV") {
        // Redirect to the IOAV dashboard
        header("Location: IOAV_dashboard.php");
        exit();
    } elseif ($role == "HOP") {
        // Display a form for HOP to choose the dashboard
        echo '<h2>Select Dashboard:</h2>';
        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
        echo '<input type="submit" name="dashboard" value="Lecturer Dashboard" formaction="lecturer_dashboard.php">';
        echo '<input type="submit" name="dashboard" value="HOP Dashboard" formaction="hop_dashboard.php">';
        echo '</form>';
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['dashboard'])) {
                // Redirect to the selected dashboard
                $selectedDashboard = $_POST['dashboard'];
                header("Location: " . strtolower(str_replace(" ", "_", $selectedDashboard)) . ".php");
                exit();
            }
        } 
    } else {
        echo '<script>alert("You have no access")</script>';
        header("Location: LoginForStaff.html");
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>