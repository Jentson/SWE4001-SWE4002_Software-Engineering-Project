<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}
$queryStaffs = "SELECT staff_id, staff_name FROM staff";
$resultStaffs = mysqli_query($con, $queryStaffs);
$staffs = mysqli_fetch_all($resultStaffs, MYSQLI_ASSOC);
if (isset($_POST['Reset'])) {
    // Retrieve staff ID and new password from the form
    $staff_id = $_POST["staff_id"];
    $new_pass = $_POST["new_pass"];

    // Hash the new password before storing it in the database
    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);

    // Update password in the database
    $sql = "UPDATE staff SET staff_pass = ? WHERE staff_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $staff_id);

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            // Password reset successful
            $message = "Password reset successfully!";
        } else {
            // Password reset failed - Staff ID not found
            $error = "Error resetting password. Staff ID not found.";
        }
    } else {
        // Password reset failed - SQL execution error
        $error = "Error resetting password. Please try again later.";
    }

    $stmt->close();    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Staff Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../images/INTI.ico">
    <script src="../formValidation.js"></script>
    <style>
        body {
            padding-top: 40px;
        }
        .container {
            max-width: 500px;
        }
    </style>
</head>
<body>
<div class = "container">
     <!-- Images -->
     <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
    </div>
    <div class="d-flex justify-content-end mb-2">
        <a href="adminmain.php" class="btn btn-secondary mb-3">Back</a>
    </div>
    <div class="card shadow">
        <div class="card-title text-center border-bottom">
            <h2 class="p-3">Reset Staff Password</h2>
        </div>

        <div class="card-body">
            <form name="resetStaffPassword" method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="staff_id" class="form-label">Staff ID:</label>
                    <select class="form-select" name="staff_id" required>
                        <option value="">Select a Staff</option>
                        <?php foreach ($staffs as $staff): ?>
                            <option value="<?php echo htmlspecialchars($staff['staff_id']); ?>">
                                <?php echo htmlspecialchars($staff['staff_name'] . ' - ' . $staff['staff_id']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="new_staff_pass" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_staff_pass" name="new_staff_pass" placeholder="abc12345" required>
                    <div class="invalid-feedback">
                        Please enter the new password.
                    </div>
                </div>
                <button type="submit" name="ResetStaff" class="btn btn-primary w-100">Reset Staff Password</button>
            </form>
        </div>
    </div>
</div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
