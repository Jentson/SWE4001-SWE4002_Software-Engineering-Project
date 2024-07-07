<?php
require_once '../database/db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Fetch staff from the database
$queryStaff = "SELECT staff_id, staff_name FROM staff";
$resultStaff = mysqli_query($con, $queryStaff);
$staff = mysqli_fetch_all($resultStaff, MYSQLI_ASSOC);

// Get the form data
if (isset($_POST['Add'])) {
    $service_name = $_POST['service_name'];
    $staff_id = $_POST['staff_id'];

    // Prepare an insert statement
    $query = "INSERT INTO service_department (service_name, staff_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param("ss", $service_name, $staff_id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted successfully."); window.location.href="addServiceDepartment.php";</script>';
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
    <title>Add Service Department</title>
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
        function validateAddServiceDepartment() {
            var service_name = document.forms["addServiceDepartment"]["service_name"].value;
            var staff_id = document.forms["addServiceDepartment"]["staff_id"].value;

            var nameError = validateField(service_name, /^[a-zA-Z0-9\s]+$/, "Special characters are not allowed in the service name.");
            var staffError = "";
            if (staff_id === "") {
                staffError = "Please select a staff member.";
            }

            if (nameError !== "" || staffError !== "") {
                alert(nameError + "\n" + staffError);
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
        <form name="addServiceDepartment" method="POST" action="" onsubmit="return validateAddServiceDepartment()">
            <div class="mb-3">
                <label for="service_name" class="form-label">Service Name:</label>
                <input type="text" class="form-control" placeholder="Service Name" name="service_name" required/>
            </div>
            <div class="mb-3">
                <label for="staff_id" class="form-label">Staff:</label>
                <select class="form-select" name="staff_id" required>
                    <option value="">Select a Staff</option>
                    <?php foreach ($staff as $staffMember): ?>
                        <option value="<?php echo htmlspecialchars($staffMember['staff_id']); ?>">
                            <?php echo htmlspecialchars($staffMember['staff_name']); ?>
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
