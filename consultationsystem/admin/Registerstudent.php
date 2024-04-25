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
                            <h2 class="p-3">Student Registration</h2>
                        </div>
                        <div class="card-body">
                            <form name="Student_Registration" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation" onsubmit="return validateForm()">
                                <!-- Add form fields for student registration -->
                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="student_id">Student ID:</label>
                                    <input class="form-control" type="text" name="student_id" id="student_id" required>
                                    <div class="invalid-feedback">
                                        Please enter Student ID
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="student_name">Student Name:</label>
                                    <input class="form-control" type="text" name="student_name" id="student_name" required>
                                    <div class="invalid-feedback">
                                        Please enter Student Name
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="student_email">Student Email:</label>
                                    <input class="form-control" type="email" name="student_email" id="student_email" required>
                                    <div class="invalid-feedback">
                                        Please enter Student Email
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="student_pass">Student Password:</label>
                                    <input class="form-control" type="password" name="student_pass" id="student_pass" required>
                                    <div class="invalid-feedback">
                                        Please enter Student Password
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select class="form-control" type="text" name="gender" id="gender" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please enter gender
                                    </div>
                                </div>

                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="program_id">Program ID:</label>
                                    <br>
                                    <select class="form-control" id="program_id" name="program_id" required>
                                    <?php
                                        include_once '../database/dbconnect.php';

                                        // Fetch programs from the database
                                        $query = "SELECT * FROM program";
                                        $result = mysqli_query($con, $query);

                                        // Check if there are any programs
                                        if (mysqli_num_rows($result) > 0) {
                                            // Loop through each program and create an option element
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['program_id'] . "'>" . $row['program_name'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No programs found</option>";
                                        }

                                        // Do not close the database connection here
                                    ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please Choose Program ID
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
    include_once '../database/dbconnect.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_id = mysqli_real_escape_string($con, $_POST['student_id']);
        $student_name = mysqli_real_escape_string($con, $_POST['student_name']);
        $student_email = mysqli_real_escape_string($con, $_POST['student_email']);
        $student_pass = mysqli_real_escape_string($con, $_POST['student_pass']);
        $hashed_password = password_hash($student_pass, PASSWORD_DEFAULT);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $program_id= mysqli_real_escape_string($con, $_POST['program_id']);

        $query = "INSERT INTO student (student_id, student_name, student_email, student_pass, gender, program_id) VALUES ('$student_id', '$student_name', '$student_email', '$hashed_password', ' $gender', '$program_id')";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "Registration successful";
        } else {
            echo "Registration failed";
        }
    }
    ?>
</body>
</html>
