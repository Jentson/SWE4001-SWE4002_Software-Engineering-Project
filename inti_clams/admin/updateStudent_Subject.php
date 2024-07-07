<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Check if student_subject_id is provided in the request
if (!isset($_POST['student_subject_id'])) {
    echo "Student Subject ID not provided.";
    exit();
}

$student_subject_id = $_POST['student_subject_id'];

// Prepare the SQL statement to fetch the student's subject details
$query = "SELECT student_subjects.*, 
                 student.student_name, 
                 subject.subject_name
          FROM student_subjects
          INNER JOIN student ON student_subjects.student_id = student.student_id
          INNER JOIN subject ON student_subjects.subject_id = subject.subject_id
          WHERE student_subjects.student_subject_id = ?";
$stmt = mysqli_prepare($con, $query);

// Bind the student_subject_id parameter to the prepared statement
mysqli_stmt_bind_param($stmt, "i", $student_subject_id);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// Check if any row is returned
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Student subject data not found.";
    exit();
}

// Close the prepared statement
mysqli_stmt_close($stmt);

// Fetch all students and subjects for selection
$queryStudents = "SELECT student_id, student_name FROM student";
$resultStudents = mysqli_query($con, $queryStudents);
$students = mysqli_fetch_all($resultStudents, MYSQLI_ASSOC);

$querySubjects = "SELECT subject_id, subject_name FROM subject";
$resultSubjects = mysqli_query($con, $querySubjects);
$subjects = mysqli_fetch_all($resultSubjects, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Subject</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Student Subject</h2>
        <a href="viewStudent_Subject.php" class="btn btn-secondary mb-3">Back</a>
        <form method="post" action="updatestudentsubject_process.php">
            <input type="hidden" name="student_subject_id" value="<?php echo $row['student_subject_id']; ?>">
            <div class="form-group">
                <label for="student_id">Student:</label>
                <select class="form-control" id="student_id" name="student_id">
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>" <?php if ($student['student_id'] == $row['student_id']) echo 'selected'; ?>>
                            <?php echo $student['student_id']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="subject_id">Subject:</label>
                <select class="form-control" id="subject_id" name="subject_id">
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['subject_id']; ?>" <?php if ($subject['subject_id'] == $row['subject_id']) echo 'selected'; ?>>
                            <?php echo $subject['subject_id']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="session">Session:</label>
                <input type="text" class="form-control" id="session" name="session" value="<?php echo $row['session']; ?>">
            </div>
            <div class="form-group">
                <label for="semester">Semester:</label>
                <input type="text" class="form-control" id="semester" name="semester" value="<?php echo $row['semester']; ?>">
            </div>
            <div class="form-group">
                <label for="section_id">Section ID:</label>
                <input type="text" class="form-control" id="section_id" name="section_id" value="<?php echo $row['section_id']; ?>">
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
