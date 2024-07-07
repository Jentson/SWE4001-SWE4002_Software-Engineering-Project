<?php
require_once "../database/db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Check if subject_id is provided in the request
if (!isset($_POST['subject_id'])) {
    echo "Subject ID not provided.";
    exit();
}

$subject_id = $_POST['subject_id'];

// Prepare the SQL statement with a placeholder for subject ID
$query = "SELECT s.*, d.department_id, d.department_name, st.staff_id, st.staff_name
          FROM subject s
          INNER JOIN department d ON s.department_id = d.department_id
          INNER JOIN staff st ON s.staff_id = st.staff_id
          WHERE s.subject_id = ?";
$stmt = mysqli_prepare($con, $query);

// Bind the subject ID parameter to the prepared statement
mysqli_stmt_bind_param($stmt, "s", $subject_id);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// Check if any row is returned
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Subject data not found.";
    exit();
}

// Close the prepared statement
mysqli_stmt_close($stmt);

// Fetch all departments from the database
$queryDepartments = "SELECT * FROM department";
$resultDepartments = mysqli_query($con, $queryDepartments);
$departments = mysqli_fetch_all($resultDepartments, MYSQLI_ASSOC);

// Fetch all staff from the database
$queryStaff = "SELECT * FROM staff";
$resultStaff = mysqli_query($con, $queryStaff);
$staff = mysqli_fetch_all($resultStaff, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Subject</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Subject</h2>
        <a href="viewSubject.php" class="btn btn-secondary mb-3">Back</a>
        <form method="post" action="updatesubject_process.php">
            <input type="hidden" name="subject_id" value="<?php echo $row['subject_id']; ?>">
            <div class="form-group">
                <label for="subject_name">Subject Name:</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo $row['subject_name']; ?>">
            </div>
            <div class="form-group">
                <label for="department_id">Faculty:</label>
                <select class="form-control" id="department_id" name="department_id">
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['department_id']; ?>" <?php if ($department['department_id'] == $row['department_id']) echo 'selected'; ?>>
                            <?php echo $department['department_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="staff_id">Staff:</label>
                <select class="form-control" id="staff_id" name="staff_id">
                    <?php foreach ($staff as $staffMember): ?>
                        <option value="<?php echo $staffMember['staff_id']; ?>" <?php if ($staffMember['staff_id'] == $row['staff_id']) echo 'selected'; ?>>
                            <?php echo $staffMember['staff_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Update</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
