<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Check if student_id is provided in the request
if (!isset($_POST['student_id'])) {
    echo "Student ID not provided.";
    exit();
}

$student_id = $_POST['student_id'];

// Prepare the SQL statement to fetch the student's details
$query = "SELECT student.*, department.department_id, department.department_name, program.program_id, program.program_name, status.status_id, status.status_name
          FROM student
          INNER JOIN department ON student.department_id = department.department_id
          INNER JOIN program ON student.program_id = program.program_id
          INNER JOIN status ON student.status_id = status.status_id
          WHERE student.student_id = ?";
$stmt = mysqli_prepare($con, $query);

// Bind the student ID parameter to the prepared statement
mysqli_stmt_bind_param($stmt, "s", $student_id);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// Check if any row is returned
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Student data not found.";
    exit();
}

// Close the prepared statement
mysqli_stmt_close($stmt);

// Fetch all departments, programs, and statuses from the database
$queryDepartments = "SELECT * FROM department";
$resultDepartments = mysqli_query($con, $queryDepartments);
$departments = mysqli_fetch_all($resultDepartments, MYSQLI_ASSOC);

$queryPrograms = "SELECT * FROM program";
$resultPrograms = mysqli_query($con, $queryPrograms);
$programs = mysqli_fetch_all($resultPrograms, MYSQLI_ASSOC);

$queryStatuses = "SELECT * FROM status";
$resultStatuses = mysqli_query($con, $queryStatuses);
$statuses = mysqli_fetch_all($resultStatuses, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Student</h2>
        <a href="viewStudents.php" class="btn btn-secondary mb-3">Back</a>
        <form method="post" action="updatestudent_process.php">
            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
            <div class="form-group">
                <label for="student_name">Student Name:</label>
                <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo $row['student_name']; ?>">
            </div>
            <div class="form-group">
                <label for="student_email">Student Email:</label>
                <input type="email" class="form-control" id="student_email" name="student_email" value="<?php echo $row['student_email']; ?>">
            </div>
            <div class="form-group">
                <label for="student_phone_number">Student Phone Number:</label>
                <input type="text" class="form-control" id="student_phone_number" name="student_phone_number" value="<?php echo $row['student_phone_number']; ?>">
            </div>
            <div class="form-group">
                <label for="student_identify_number">Student Identity Number:</label>
                <input type="text" class="form-control" id="student_identify_number" name="student_identify_number" value="<?php echo $row['student_identify_number']; ?>">
            </div>
            <div class="form-group">
                <label for="student_address">Student Address:</label>
                <input type="text" class="form-control" id="student_address" name="student_address" value="<?php echo $row['student_address']; ?>">
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <select class="form-control" id="state" name="state">
                    <?php if ($row['state'] == 'Local') { ?>
                        <option value="Local" selected>Local</option>
                        <option value="International">International</option>
                    <?php } else { ?>
                        <option value="Local">Local</option>
                        <option value="International" selected>International</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="department_id">Department:</label>
                <select class="form-control" id="department_id" name="department_id">
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['department_id']; ?>" <?php if ($department['department_id'] == $row['department_id']) echo 'selected'; ?>>
                            <?php echo $department['department_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="program_id">Program:</label>
                <select class="form-control" id="program_id" name="program_id">
                    <?php foreach ($programs as $program): ?>
                        <option value="<?php echo $program['program_id']; ?>" <?php if ($program['program_id'] == $row['program_id']) echo 'selected'; ?>>
                            <?php echo $program['program_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status_id">Status:</label>
                <select class="form-control" id="status_id" name="status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status['status_id']; ?>" <?php if ($status['status_id'] == $row['status_id']) echo 'selected'; ?>>
                            <?php echo $status['status_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="Male" <?php if ($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
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
