<?php
require_once '../database/dbconnect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../intizh/login.php";</script>';
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Process form data
    if (isset($_POST['Register'])) {
        // Retrieve form input values
        $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
        $student_name = mysqli_real_escape_string($con, $_POST['student_name']);
        $student_email = mysqli_real_escape_string($con, $_POST['student_email']);
        $student_pass = mysqli_real_escape_string($con, $_POST['student_pass']);
        $hashed_password = password_hash($student_pass, PASSWORD_DEFAULT);
        $student_phone = mysqli_real_escape_string($con, $_POST['student_phone']);
        $student_identify_number = mysqli_real_escape_string($con, $_POST['student_identify_number']);
        $student_address = mysqli_real_escape_string($con, $_POST['student_address']);
        $state = mysqli_real_escape_string($con, $_POST['state']);
        $department_id = mysqli_real_escape_string($con, $_POST['department_id']);
        $program_id = mysqli_real_escape_string($con, $_POST['program_id']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $status = "2";

        // Insert data into the database
        $query = "INSERT INTO student (student_id, student_name, student_email, student_pass, student_phone_number, student_identify_number, student_address, state, department_id, program_id, gender, status_id) 
                    VALUES ('$student_id', '$student_name', '$student_email', '$hashed_password', '$student_phone' , '$student_identify_number' , '$student_address' , '$state' ,  '$department_id' , '$program_id' , ' $gender', '$status')";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo '<script>alert("Registration successful");</script>';
            echo '<script>window.location.href = "viewStudents.php";</script>';
            exit();
        } else {
            echo '<script>alert("Registration failed: ' . mysqli_error($con) . '");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <section class="page2">
        <!-- Images -->
        <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>

        <div class="container">

        <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
        <div class="d-flex justify-content-between">
            <a href="adminmain.php" class="btn btn-secondary ms-auto mb-2">Back</a>
        </div>
        <div class="card shadow">
            <div class="card-title text-center border-bottom">
                <h2 class="p-3">Student Registration</h2>
            </div>
            
            <div class="card-body">
                <form name="Add Student" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation" onsubmit="return validateForm()">
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_id">Student ID:</label>
                            <input class="form-control" type="text" name="student_id" id="student_id" required pattern="J.*">
                            <div class="invalid-feedback" id="student_id_feedback">
                                        Please enter a Student ID starting with 'J'.
                                    </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_name">Student Name:</label>
                            <input class="form-control" type="text" name="student_name" id="student_name" required>
                            <div class="invalid-feedback" id="student_name_feedback">
                                Please enter Student Name
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_email">Student Email:</label>
                            <input class="form-control" type="email" name="student_email" id="student_email" required readonly>
                            <div class="invalid-feedback" id="student_email_feedback">
                                        Please enter a valid student email (e.g., "StudentID"@student.newinti.edu.my).
                                    </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Second Column -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_pass">Student Password:</label>
                            <input class="form-control" type="password" name="student_pass" id="student_pass" required>
                            <div class="invalid-feedback" id="student_pass_feedback">
                                        Please enter a password with at least 6 characters.
                                    </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_phone">Phone Number:</label>
                            <input class="form-control" type="number" name="student_phone" id="student_phone" required>
                            <div class="invalid-feedback" id="student_phone_feedback">
                                Please enter Phone Number
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_identify_number">IC/Passport:</label>
                            <input class="form-control" type="number" name="student_identify_number" id="student_identify_number" required>
                            <div class="invalid-feedback" id="student_identify_number_feedback">
                                Please enter IC/Passport Number
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Third Column -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="student_address">Address:</label>
                            <input class="form-control" type="text" name="student_address" id="student_address" required>
                            <div class="invalid-feedback">
                                Please enter Address
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                        <label class="form-label" for="state">Local/International Student</label>
                                    <select class="form-control" name="state" id="state" required>
                                    <option value="">Please Select your state</option>
                                    <option value="Local">Local</option>
                                    <option value="International">International</option>
                                    </select>
                            <div class="invalid-feedback">
                                        Please select the state of student
                                    </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="gender">Gender:</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Please Select your Gender</option>
                                <option value='Male'>Male</option>
                                <option value='Female'>Female</option>
                            </select>
                            <div class="invalid-feedback">
                                Please Choose Gender
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Fourth Column -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="department_id">Faculty:</label>
                            <select class="form-control" id="department_id" name="department_id" onchange="getPrograms(this.value)" required>
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
                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="program_id">Program ID:</label>
                            <select class="form-control" id="program_id" name="program_id" required>
                                <option value=''>Select Department First</option>
                            </select>
                            <div class="invalid-feedback">
                                Please Choose Program Code
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary" name="Register" type="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    </section>
     <!-- JavaScript function to fetch programs based on selected department -->
     <script>
        function getPrograms(department_id) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("program_id").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "get_student_programs.php?department_id=" + department_id, true);
            xhttp.send();
        }
    </script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all form elements
        var formElements = document.querySelectorAll(".form-control");
        
        // Add input event listener to each form element
        formElements.forEach(function(element) {
            element.addEventListener("input", function(event) {
                validateField(event.target);
            });
        });
    });

    document.getElementById('student_id').addEventListener('input', function() {
        var studentId = document.getElementById('student_id').value.toLowerCase();
        document.getElementById('student_email').value = studentId + '@student.newinti.edu.my';
    });
</script>

<!-- JavaScript function to fetch programs based on selected department -->

<script>
document.getElementById('student_email').addEventListener('input', function() {
    var emailInput = this;
    var emailPattern = /^[a-zA-Z0-9._%+-]+@student\.newinti\.edu\.my$/;
    var emailFeedback = document.getElementById('student_email_feedback');
    
    if (emailInput.value.match(emailPattern)) {
        emailInput.setCustomValidity('');
        emailFeedback.style.display = 'none';
    } else {
        emailInput.setCustomValidity('Invalid');
        emailFeedback.style.display = 'block';
    }
});

function validateField(field) {
    var feedbackId = field.id + "_feedback";
    var feedbackElement = document.getElementById(feedbackId);
    
    if (feedbackElement) {
        if (field.checkValidity()) {
            feedbackElement.style.display = "none";
        } else {
            feedbackElement.style.display = "block";
        }
    }
}

</script>