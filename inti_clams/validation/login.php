<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="../css/styles.css"> Include your CSS file -->

    <script>
        function handleForgotPassword() {
            if (confirm("This process will take about 1-2 days, after this your account will be Unusable. Do you wish to proceed?")) {
                window.location.href = "forgotPassword.php";
            }
        }
    </script>
</head>
<body>
  
        <!-- Images -->
        <div class="row justify-content-center">
            <div class="col-10">
            <a href="../index.php">
            <img src="../images/Inti_X_IICS-CLAMS.png"class="img-fluid rounded mx-auto d-block"  alt="INTI logo"><br>
            </a>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card shadow">
                        <div class="card-title text-center border-bottom">
                            <h2 class="p-3">Login</h2>
                        </div>
                        <div class="card-body">
                            <form name="LoginForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation">
                                <div class="mb-4 form-group was-validated">
                                    <label class="form-label" for="id">IICS-ID</label>
                                    <input class="form-control" type="text" name="id" id="id" required>
                                    <div class="invalid-feedback">
                                        Please enter your ID
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="pass">Password</label>
                                    <input class="form-control" type="password" name="pass" id="pass" required>
                                    <div class="invalid-feedback">
                                        Please enter your password
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" name="Login">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </button>
                                </div>
                                <br>
                                <div class="d-grid">
                                    <button class="btn btn-primary" onclick="handleForgotPassword()">Forgotten Password?</button>
                                </div>
                                <br>
                                <div class="d-grid">
                                    <button class="btn btn-primary" onclick="window.location.href = '../index.php';">Back to Main</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <?php
    session_start();
    $conn = new mysqli('localhost', 'root', '', 'intiservice');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['Login'])) {
        $id = $_POST['id'];
        $pass = $_POST['pass'];
        $firstLetter = strtoupper($id[0]);

        // Check the first letter of the ID

            // Staff login attempt
            $result_staff = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = '$id'");
            $row_staff = mysqli_fetch_array($result_staff);

            if ($row_staff && password_verify($pass, $row_staff['staff_pass'])) {
                if (in_array($row_staff['position_id'], ['1', '2', '3'])) {
                    // Staff login successful
                    $_SESSION['Staff_id'] = $id;
                    $_SESSION['staff_name'] = $row_staff['staff_name'];
                    header("Location: ../staff/StaffMain.php");
                    exit();
                } elseif ($row_staff['position_id'] == '4') {
                    // Admin login successful
                    $_SESSION['Staff_id'] = $id;
                    $_SESSION['staff_name'] = $row_staff['staff_name'];
                    header("Location: ../admin/adminMain.php");
                    exit();
                }
            } else {
                // echo '<script>alert("Invalid Staff ID or Password.");</script>';
            }

    if ($firstLetter == 'J') {
        // Student login attempt
        $result_student = mysqli_query($conn, "SELECT * FROM student WHERE student_id = '$id'");
        $row_student = mysqli_fetch_array($result_student);

        if ($row_student && password_verify($pass, $row_student['student_pass'])) {
            if ($row_student['status_id'] == '2') {
                // Student login successful
                $_SESSION['student_id'] = $id;
                $_SESSION['student_name'] = $row_student['student_name'];
                header("Location: ../student/StudentMain.php");
                exit();
            } elseif ($row_student['status_id'] == '1') {
                echo '<script>alert("Pending Admin approval.");</script>';
            }
        } else {
            echo '<script>alert("Invalid Student ID or Password.");</script>';
        }
    } else {
        echo '<script>alert("Please follow the INTI format ID to Login.");</script>';
    }
}

mysqli_close($conn);
?>

</body>
</html>
