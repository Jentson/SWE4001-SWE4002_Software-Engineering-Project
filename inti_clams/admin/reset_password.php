<?php
include_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);

    // Fetch the token from the database
    $query = "SELECT * FROM password_resets WHERE token = '$token' AND expiry > NOW()";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Token is valid, show the password reset form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center mb-4">Reset Password</h2>
                                <form method="POST" action="reset_password.php">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo 'Invalid or expired token.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = mysqli_real_escape_string($con, $_POST['token']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Fetch the token from the database
    $query = "SELECT * FROM password_resets WHERE token = '$token' AND expiry > NOW()";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $student_id = $row['student_id'];

        // Update the student's password
        $query = "UPDATE student SET student_pass = '$hashed_password' WHERE student_id = '$student_id'";
        mysqli_query($con, $query);

        // Delete the used token
        $query = "DELETE FROM password_resets WHERE token = '$token'";
        mysqli_query($con, $query);

        echo 'Password has been reset successfully.';
        echo 'Please close this window.';
    } else {
        echo 'Invalid or expired token.';
    }
}
?>
