<?php
require_once "../database/db.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}
$queryStudents = "SELECT student_id, student_name FROM student";
$resultStudents = mysqli_query($con, $queryStudents);
$students = mysqli_fetch_all($resultStudents, MYSQLI_ASSOC);
if (isset($_POST['Reset'])) {
    // Retrieve email and new password from the form
    $student_id = $_POST["student_id"];
    $new_pass = $_POST["new_pass"];

    // Hash the new password before storing it in the database
    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);

    // Update password in the database
    $sql = "UPDATE student SET student_pass = ? WHERE student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $student_id);

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            // Password reset successful
            $message = "Password reset successfully!";
        } else {
            // Password reset failed - Student ID not found
            $error = "Error resetting password. Student ID not found.";
        }
    } else {
        // Password reset failed - SQL execution error
        $error = "Error resetting password. Please try again later.";
    }

    $stmt->close();    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../images/INTI.ico">
    <script src="../formValidation.js"></script>
    <style>
        body {
            padding-top: 40px;
        }
        .container {
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Images --> 
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>

        <div class="d-flex justify-content-end mb-2">
                <a href="adminmain.php" class="btn btn-secondary">Back</a>
            </div>
     <div class="container mt-5">
    <div class="card shadow">
        <div class="card-title text-center border-bottom">
            <h2 class="p-3">Reset Password</h2>
        </div>
        <div class="card-body">
            <?php if (isset($message)) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form name="resetPassword" method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID:</label>
                    <select class="form-select" name="student_id" required>
                        <option value="">Select a Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo htmlspecialchars($student['student_id']); ?>">
                                <?php echo htmlspecialchars($student['student_name'] . ' - ' . $student['student_id']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="new_pass" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="abc12345" required>
                    <div class="invalid-feedback">
                        Please enter the new password.
                    </div>
                </div>
                <button type="submit" name="Reset" class="btn btn-primary w-100">Reset</button>
            </form>
        </div>
    </div>
</div>


    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
