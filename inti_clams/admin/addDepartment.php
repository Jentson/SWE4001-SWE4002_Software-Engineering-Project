<?php
require_once '../database/db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Get the form data
if (isset($_POST['Add'])) {
    $department_name = $_POST['department_name'];

    // Prepare an insert statement
    $query = "INSERT INTO department (department_name) VALUES (?)";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("s", $department_name);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted successfully."); window.location.href="addDepartment.php";</script>';
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
    <title>Add Faculty Name</title>
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
        function validateAddDepartment() {
            var department_name = document.forms["addDepartment"]["department_name"].value;

            var nameError = validateField(department_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the name.");

            if (nameError !== "") {
                alert(nameError);
                return false;
            }
            return true;
        }

        function validateField(value, regex, errorMsg) {
            if (!regex.test(value)) {
                return errorMsg;
            }
            return "";
        }
    </script>
    
    <div class="container">
        <form name="addDepartment" method="POST" action="" onsubmit="return validateAddDepartment()">
            <div class="mb-3">
                <label for="department_name" class="form-label">Faculty Name:</label>
                <input type="text" class="form-control" placeholder="Faculty Name" name="department_name" required/>
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
