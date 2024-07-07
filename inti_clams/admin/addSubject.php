<?php
require_once '../database/db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Fetch departments from the database
$queryDepartments = "SELECT department_id, department_name FROM department";
$resultDepartments = mysqli_query($con, $queryDepartments);
$departments = mysqli_fetch_all($resultDepartments, MYSQLI_ASSOC);

// Fetch staff from the database
$queryStaff = "SELECT staff_id, staff_name FROM staff WHERE position_id NOT IN (3, 4)";
$resultStaff = mysqli_query($con, $queryStaff);
$staff = mysqli_fetch_all($resultStaff, MYSQLI_ASSOC);

// Get the form data
if (isset($_POST['Add'])) {
    $subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];
    $department_id = $_POST['department_id'];
    $staff_id = $_POST['staff_id'];

    // Prepare an insert statement
    $query = "INSERT INTO subject (subject_id, subject_name, department_id, staff_id) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("ssis", $subject_id, $subject_name, $department_id, $staff_id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted successfully."); window.location.href="viewSubject.php";</script>';
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
    <title>Add Subject</title>
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

    <script>
        function validateAddSubject() {
            var subject_id = document.forms["addSubject"]["subject_id"].value;
            var subject_name = document.forms["addSubject"]["subject_name"].value;
            var department_id = document.forms["addSubject"]["department_id"].value;
            var staff_id = document.forms["addSubject"]["staff_id"].value;

            var idError = validateField(subject_id, /^[A-Za-z]{3}\d{5}$/, "Subject ID must be 3 alphabets followed by 5 digits.");
            var nameError = validateField(subject_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");
            var departmentError = "";
            if (department_id === "") {
                departmentError = "Please select a department.";
            }

            if (idError !== "" || nameError !== "" || departmentError !== "") {
                alert(idError + "\n" + nameError + "\n" + departmentError);
                return false;
            }
            return true;
        }
    </script>
    <div class="container">
        <form name="addSubject" method="POST" action="" onsubmit="return validateAddSubject()">
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject ID:</label>
                <input type="text" class="form-control" placeholder="abc12345" name="subject_id" required/>   
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" placeholder="Introduction to Programming" name="subject_name" required/>
            </div>

            <div class="mb-3">
            <label class="form-label" for="department_id">Faculty:</label>
                <select class="form-control" id="department_id" name="department_id" onchange="getStaff(this.value)" required>
                <option value=''>Select Faculty First</option>
                    <?php
                    include_once '../database/dbconnect.php';
                    // Fetch departments from the database
                    $query = "SELECT * FROM department";
                    $result = mysqli_query($con, $query);
                    // Check if there are any departments
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each department and create an option element
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['department_id'] . "'>" . $row['department_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No Department found</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Please Select Department Name
                </div>
            </div>

            <div class="mb-3">
            <label class="form-label" for="staff_id">Staff Name:</label>
                <select class="form-control" id="staff_id" name="staff_id" required>
                    <option value=''>Select Staff First</option>
                </select>
                <div class="invalid-feedback">
                    Please Choose Staff
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-2" name="Add">Add</button>
                <a href="adminmain.php" class="btn btn-secondary mb-3 align-top">Back</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script>
        function getStaff(department_id) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("staff_id").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "get_staff.php?department_id=" + department_id, true);
            xhttp.send();
        }
    </script>
</body>
</html>
