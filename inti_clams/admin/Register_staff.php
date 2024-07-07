<?php
require_once "../database/dbconnect.php";
session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../intizh/login.php";</script>';
    session_destroy();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Registration page for staff -->

<head>
    <meta charset="utf-8" />
    <meta name="description" content="SWE20004" />
    <meta name="keywords" content="HTML,CSS,Javascript" />
    <meta name="author" content="Eazy 4 Leave" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="styles/style.css" rel="stylesheet">
    <link href="styles/responsive.css" rel="stylesheet">
    <link href="styles/LoginForStaff.css" rel="stylesheet">
    <title>Register Staff</title>
</head>

<script>
    function validateForm() {
        var staff_id = document.forms["StaffRegistration"]["staff_id"].value;
        var staff_name = document.forms["StaffRegistration"]["staff_name"].value;
        var staff_email = document.forms["StaffRegistration"]["staff_email"].value;
        var staff_pass = document.forms["StaffRegistration"]["staff_pass"].value;
        var staff_identity_number = document.forms["StaffRegistration"]["staff_identity_number"].value;
        var staff_address = document.forms["StaffRegistration"]["staff_address"].value;
        var phone_number = document.forms["StaffRegistration"]["phone_number"].value;
        var position_id = document.forms["StaffRegistration"]["position_id"].value;
        var department_id = document.forms["StaffRegistration"]["department_id"].value;
        var gender = document.forms["StaffRegistration"]["gender"].value;
        var errors = [];


        // Validation for Staff Name
        if (staff_name == "") {
            errors.push("Please enter Staff Name.");
        }

        // Validation for Staff Email
        if (staff_email == "") {
            errors.push("Please enter Staff Email.");
        } else {
            // Simple email validation
            if (!/^\S+@\S+\.\S+$/.test(staff_email)) {
                errors.push("Please enter a valid email address.");
            }
        }

        // Validation for Password
        if (staff_pass == "") {
            errors.push("Please enter Password.");
        } else {
            // At least one lowercase letter
            if (/[!@#$%^&*(),.?":{}|<>]/.test(staff_pass)) {
                errors.push("Special Characters are not allowed in the password.");
            }
        }

        if (staff_identity_number == "") {
            errors.push("Please enter Staff Name.");
        }

        if (staff_address == "") {
            errors.push("Please enter Staff Name.");
        }

        if (phone_number == "") {
            errors.push("Please enter Staff Name.");
        }

        // Validation for Position ID
        if (position_id == "") {
            errors.push("Please select Position ID.");
        }

        // Validation for Department ID
        if (department_id == "") {
            errors.push("Please select Department ID.");
        }

        // Validation for Gender
        if (gender == "") {
            errors.push("Please select Gender.");
        }

        // If no errors when filling the data, form can be submitted 
        if (errors.length > 0) {
            alert(errors.join("\n"));
            return false;
        }
        return true;
    }
</script>

<body>
        <!-- Images -->
        <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
        <section class="page2">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
            <div class="d-flex justify-content-end mb-2">
                <a href="adminmain.php" class="btn btn-secondary">Back</a>
            </div>

                <div class="card shadow">
                    <div class="card-title text-center border-bottom">
                        <h2 class="p-3">Staff Registration</h2>
                    </div>
                    <div class="card-body">
                        <form name="StaffRegistration" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation" onsubmit="return validateForm()">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Staff ID -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_id">Staff ID</label>
                                        <input class="form-control" type="text" name="staff_id" id="staff_id" required>
                                        <div class="invalid-feedback">
                                            Please enter Staff ID
                                        </div>
                                    </div>

                                    <!-- Staff Name -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_name">Staff Name</label>
                                        <input class="form-control" type="text" name="staff_name" id="staff_name" required>
                                        <div class="invalid-feedback">
                                            Please enter Staff Name
                                        </div>
                                    </div>

                                    <!-- Staff Email -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_email">Staff Email</label>
                                        <input class="form-control" type="email" name="staff_email" id="staff_email" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Password -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_pass">Password</label>
                                        <input class="form-control" type="password" name="staff_pass" id="staff_pass" required>
                                        <div class="invalid-feedback">
                                            Please enter Password
                                        </div>
                                    </div>

                                    <!-- IC/Passport -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_identity_number">IC/Passport</label>
                                        <input class="form-control" type="text" name="staff_identity_number" id="staff_identity_number" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid identity number
                                        </div>
                                    </div>

                                    <!-- Staff Address -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="staff_address">Staff Address</label>
                                        <input class="form-control" type="text" name="staff_address" id="staff_address" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid staff address
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Phone number -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="phone_number">Phone number</label>
                                        <input class="form-control" type="text" name="phone_number" id="phone_number" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid phone number
                                        </div>
                                    </div>

                                    <!-- Position ID -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="position_id">Position ID:</label>
                                        <select class="form-control" id="position_id" name="position_id" required>
                                        <option value="">Please Select your Position</option>
                                            <?php
                                            include_once '../database/dbconnect.php';

                                            // Fetch positions from the database
                                            $query = "SELECT * FROM position";
                                            $result = mysqli_query($con, $query);

                                            // Check if there are any positions
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through each position and create an option element
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<option value='" . $row['position_id'] . "'>" . $row['position_name'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No positions found</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please Choose position id
                                        </div>
                                    </div>

                                    <!-- Department ID -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="department_id">Department ID</label><br>
                                        <select class="form-control" name="department_id" id="department_id" required>
                                        <option value="">Please Select your Department Name</option>
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
                                                echo "<option value=''>No departments found</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter department id
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="mb-4 form-group was-validated">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select class="form-control" type="text" name="gender" id="gender" required>
                                            <option value="">Please Select your Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter gender
                                        </div>
                                    </div>
                                </div>
                            </div>
                     

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="Register">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <?php
    
    $conn = new mysqli('localhost', 'root', '', 'intiservice');
    if (isset($_POST['Register'])) {
        // Retrieve form data
        $staff_id = $_POST['staff_id'];
        $staff_name = $_POST['staff_name'];
        $staff_email = $_POST['staff_email'];
        $staff_pass = $_POST['staff_pass'];
        $staff_identity_number = $_POST['staff_identity_number'];
        $staff_address = $_POST['staff_address'];
        $phone_number = $_POST['phone_number'];
        $position_id = $_POST['position_id'];
        $department_id = $_POST['department_id'];
        $gender = $_POST['gender'];

        // Hash the password
        $hashed_password = password_hash($staff_pass, PASSWORD_DEFAULT);

        // Insert data into the staff table
        $insert_query = "INSERT INTO staff (staff_id, staff_name, staff_email, staff_pass, staff_identity_number, staff_address, phone_number, position_id, department_id, gender) VALUES ('$staff_id', '$staff_name', '$staff_email', '$hashed_password', '$staff_identity_number', '$staff_address', '$phone_number', '$position_id', '$department_id', '$gender')";

        // Assume you have already established the $conn connection and defined $insert_query
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Registration successful');</script>";
            echo '<script>window.location.href = "viewstaff.php";</script>';
        } else {
            echo "<script>
                    alert('Error: " . addslashes(mysqli_error($conn)) . "');
                  </script>";
        }
    }
    mysqli_close($conn);
    ?>

</body>

</html>
