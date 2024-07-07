<?php
require_once '../database/db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Fetch subjects from the database
$querySubjects = "SELECT subject_id, subject_name FROM subject";
$resultSubjects = mysqli_query($con, $querySubjects);
if ($resultSubjects === false) {
    die('Error fetching subjects: ' . mysqli_error($con));
}
$subjects = mysqli_fetch_all($resultSubjects, MYSQLI_ASSOC);

// Fetch students from the database
$queryStudents = "SELECT student_id, student_name FROM student";
$resultStudents = mysqli_query($con, $queryStudents);
if ($resultStudents === false) {
    die('Error fetching students: ' . mysqli_error($con));
}
$students = mysqli_fetch_all($resultStudents, MYSQLI_ASSOC);

// Get the form data
if (isset($_POST['Add'])) {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $session = $_POST['session'];
    $semester = $_POST['semester'];
    $section_id = $_POST['section_id'];

    // Fetch program_id based on the selected student_id
    $query = "SELECT program_id FROM student WHERE student_id = ?";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($program_id);
    $stmt->fetch();
    $stmt->close();

    // Prepare an insert statement
    $query = "INSERT INTO student_subjects (student_id, subject_id, program_id, session, semester, section_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("ssisss", $student_id, $subject_id, $program_id, $session, $semester, $section_id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted successfully."); window.location.href="addStudentSubject.php";</script>';
    } else {
        echo '<script>alert("Unable to insert data: ' . $stmt->error . '"); window.history.go(-1);</script>';
    }

    // Close statement (not needed here)
    //$stmt->close();
}

// Do not close the connection here, as you might perform additional operations later
// mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student Subject</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="styles/style.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
    <header>
        <!-- Images -->
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
            <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
        </a>
        </div>

    </header>
    <div class="container">
        <form method="POST" action="" class="needs-validation">
        <div class="mb-3">
                <label for="student_id" class="form-label">Student ID:</label>
                <select class="form-select" name="student_id" id="student_id" required onchange="updateSubjects()">
                    <option value="">Select a Student</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['student_id']); ?>">
                            <?php echo htmlspecialchars($student['student_name'] . ' - ' . $student['student_id']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject:</label>
                <select class="form-select" name="subject_id" id="subject_id" required>
                    <option value="">Select a Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject['subject_id']); ?>">
                            <?php echo htmlspecialchars($subject['subject_name'] . ' - ' . $subject['subject_id']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="session" class="form-label">Session:</label>
                <input type="text" class="form-control" placeholder="Session" name="session" required/>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester:</label>
                <input type="number" class="form-control" placeholder="Semester" name="semester" required/>
            </div>
            <div class="mb-3">
                <label for="section_id" class="form-label">Section ID:</label>
                <input type="text" class="form-control" placeholder="Section ID" name="section_id" required/>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-2" name="Add">Add</button>
                <a href="adminmain.php" class="btn btn-secondary mb-3 align-top">Back</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script>
    function updateSubjects() {
        var student_id = document.getElementById("student_id").value;
        if (student_id === "") {
            document.getElementById("subject_id").innerHTML = '<option value="">Select a Subject</option>';
            return;
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("subject_id").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "getSubjects.php?student_id=" + student_id, true);
        xhttp.send();
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>