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
        var position_id = document.forms["StaffRegistration"]["position_id"].value;
        var program_id = document.forms["StaffRegistration"]["program_id"].value;
        var gender = document.forms["StaffRegistration"]["gender"].value;
        var errors = [];

        // Validation for Staff ID
        if (staff_id == "") {
            errors.push("Please enter Staff ID.");
        } else {
            if (!/^\d{6}/.test(staff_id)) {
                errors.push("Please enter a valid Staff ID (6 digits).");
            }
        }

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

        // Validation for Position ID
        if (position_id == "") {
            errors.push("Please select Position ID.");
        }

        // Validation for Program ID
        if (program_id == "") {
            errors.push("Please select Program ID.");
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
    <section class="page2">
        <header>
            <div class="text-center">
                <img class="img-fluid img-thumbnail" src="images/pic5.png" alt="INTI Logo" />
            </div>
        </header>

        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card shadow">
                        <div class="card-title text-center border-bottom">
                            <h2 class="p-3">Staff Registration</h2>
                        </div>
                        <div class="card-body">
                            <form name="StaffRegistration" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation" onsubmit="return validateForm()">
                                <!-- Add form fields for staff registration -->
                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="staff_id">Staff ID</label>
                                    <input class="form-control" type="text" name="staff_id" id="staff_id" required>
                                    <div class="invalid-feedback">
                                        Please enter Staff ID
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="staff_name">Staff Name</label>
                                    <input class="form-control" type="text" name="staff_name" id="staff_name" required>
                                    <div class="invalid-feedback">
                                        Please enter Staff Name
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="staff_email">Staff Email</label>
                                    <input class="form-control" type="email" name="staff_email" id="staff_email" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="staff_pass">Password</label>
                                    <input class="form-control" type="password" name="staff_pass" id="staff_pass" required>
                                    <div class="invalid-feedback">
                                        Please enter Password
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="position_id">Position ID</label>
                                    <input class="form-control" type="text" name="position_id" id="position_id" required>
                                    <div class="invalid-feedback">
                                        Please enter position id
                                    </div>
                                </div>

                                <<div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="program_id">Program ID SUT is 1</label>
                                    <input class="form-control" type="text" name="program_id" id="program_id" required>
                                    <div class="invalid-feedback">
                                        Please enter program id
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="gender">Gender</label>
                                    <input class="form-control" type="text" name="gender" id="gender" required>
                                    <div class="invalid-feedback">
                                        Please enter gender
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
        $position_id = $_POST['position_id'];
        $program_id = $_POST['program_id'];
        $gender = $_POST['gender'];

        // Hash the password
        $hashed_password = password_hash($staff_pass, PASSWORD_DEFAULT);

        // Insert data into the staff table
        $insert_query = "INSERT INTO staff (staff_id, staff_name, staff_email, staff_pass, position_id, program_id, gender) VALUES ('$staff_id', '$staff_name', '$staff_email', '$hashed_password', '$position_id', '$program_id', '$gender')";

        if (mysqli_query($conn, $insert_query)) {
            echo "Registration successful";
            // You may choose to redirect to a different page after successful registration
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
    ?>

</body>

</html>
