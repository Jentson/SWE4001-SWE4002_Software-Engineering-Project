<?php
require_once '../database/db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Fetch departments from the database for the dropdown list
$queryDepartments = "SELECT department_id, department_name FROM department";
$resultDepartments = mysqli_query($con, $queryDepartments);
$departments = mysqli_fetch_all($resultDepartments, MYSQLI_ASSOC);

// Get the form data
if (isset($_POST['Add'])) {
    $program_name = $_POST['program_name'];
    $department_id = $_POST['department_id'];

    // Prepare an insert statement
    $query = "INSERT INTO program (program_name, department_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("si", $program_name, $department_id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted successfully."); window.location.href="addProgram.php";</script>';
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
    <title>Add Program</title>
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
    <script>
        function validateAddProgram() {
            var program_name = document.forms["addProgram"]["program_name"].value;
            var department_id = document.forms["addProgram"]["department_id"].value;

            var nameError = validateField(program_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");
            var departmentError = "";
            if (department_id === "") {
                departmentError = "Please select a department.";
            }

            if (nameError !== "" || departmentError !== "") {
                alert(nameError + "\n" + departmentError);
                return false;
            }
            return true;
        }
    </script>
    <div class="container">
        <form name="addProgram" method="POST" action="" onsubmit="return validateAddProgram()">
            <div class="mb-3">
                <label for="program_name" class="form-label">Program Name:</label>
                <input type="text" class="form-control" placeholder="Program Name" name="program_name" required/>
            </div>
            <div class="mb-3">
                <label for="department_id" class="form-label">Faculty:</label>
                <select class="form-select" name="department_id" required>
                    <option value="">Select a Faculty</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-2" name="Add">Add</button>
                <a href="adminmain.php" class="btn btn-secondary mb-3 align-top">Back</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
