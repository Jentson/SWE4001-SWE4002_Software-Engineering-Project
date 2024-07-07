<?php
require_once "../database/db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Check if staff_id is provided in the request
if (!isset($_POST['staff_id'])) {
    echo "Staff ID not provided.";
    exit();
}

$staff_id = $_POST['staff_id'];

// Fetch staff data based on staff_id
$query = "SELECT s.*, p.position_id, p.position_name, d.department_id, d.department_name 
          FROM staff s 
          INNER JOIN position p ON s.position_id = p.position_id
          INNER JOIN department d ON s.department_id = d.department_id
          WHERE s.staff_id = $staff_id";
$result = mysqli_query($con, $query);

// Check if any row is returned
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Staff data not found.";
    exit();
}

// Fetch all positions from the database
$queryPositions = "SELECT * FROM position";
$resultPositions = mysqli_query($con, $queryPositions);
$positions = mysqli_fetch_all($resultPositions, MYSQLI_ASSOC);

// Fetch all departments from the database
$queryDepartments = "SELECT * FROM department";
$resultDepartments = mysqli_query($con, $queryDepartments);
$departments = mysqli_fetch_all($resultDepartments, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Staff</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>Update Staff</h2>
        <a href="viewStaff.php" class="btn btn-secondary mb-3">Back</a>
        <form method="post" action="updatestaff_process.php">
            <input type="hidden" name="staff_id" value="<?php echo $row['staff_id']; ?>">
            <div class="mb-3">
                <label for="staffName" class="form-label">Staff Name:</label>
                <input type="text" id="staffName" name="staff_name" class="form-control" value="<?php echo $row['staff_name']; ?>">
            </div>
            <div class="mb-3">
                <label for="staffEmail" class="form-label">Staff Email:</label>
                <input type="email" id="staffEmail" name="staff_email" class="form-control" value="<?php echo $row['staff_email']; ?>">
            </div>
            <div class="mb-3">
                <label for="staffIdentityNumber" class="form-label">Staff Identity Number:</label>
                <input type="text" id="staffIdentityNumber" name="staff_identity_number" class="form-control" value="<?php echo $row['staff_identity_number']; ?>">
            </div>
            <div class="mb-3">
                <label for="staffAddress" class="form-label">Staff Address:</label>
                <input type="text" id="staffAddress" name="staff_address" class="form-control" value="<?php echo $row['staff_address']; ?>">
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phone_number" class="form-control" value="<?php echo $row['phone_number']; ?>">
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position:</label>
                <select id="position" name="position" class="form-select">
                    <?php foreach ($positions as $position): ?>
                        <option value="<?php echo $position['position_id']; ?>" <?php if ($position['position_id'] == $row['position_id']) echo 'selected'; ?>>
                            <?php echo $position['position_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <select id="department" name="department" class="form-select">
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['department_id']; ?>" <?php if ($department['department_id'] == $row['department_id']) echo 'selected'; ?>>
                            <?php echo $department['department_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" id="maleGender" name="gender" value="Male" class="form-check-input" <?php if ($row['gender'] == 'Male') echo 'checked'; ?>>
                    <label for="maleGender" class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="femaleGender" name="gender" value="Female" class="form-check-input" <?php if ($row['gender'] == 'Female') echo 'checked'; ?>>
                    <label for="femaleGender" class="form-check-label">Female</label>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
