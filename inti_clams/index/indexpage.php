<?php
session_start();

// Check if the user is logged in and has a user ID set
if (!isset($_SESSION['Staff_id']) && !isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// Determine user type for the greeting message
$user_name = '';
if (isset($_SESSION['staff_name'])) {
    $user_name = $_SESSION['staff_name']; // Staff name
} elseif (isset($_SESSION['student_name'])) {
    $user_name = $_SESSION['student_name']; // Student name
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Selection</title>
    <link rel="stylesheet" href="consultationsystem/css/styles.css"> <!-- Include your CSS file -->
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5 w-50 mx-auto">
        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-secondary btn-sm me-2" href="settings.php">Settings</a>
            <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Hi, <?php echo htmlspecialchars($user_name); ?></h1>
                <p class="lead">Dashboard:</p>
                <form action="navigate.php" method="post" class="mt-4">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg mb-3" type="submit" name="folder" value="consultation">Consultation</button>
                        <button class="btn btn-secondary btn-lg" type="submit" name="folder" value="leave">Leave</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
