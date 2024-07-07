<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="consultationsystem/css/styles.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card shadow">
                    <div class="card-title text-center border-bottom">
                        <h2 class="p-3">Forgot Password</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="needs-validation">
                            <div class="mb-3 form-group was-validated">
                                <label class="form-label" for="student_id">IICS-ID</label>
                                <input class="form-control" type="text" name="student_id" id="student_id" required>
                                <div class="invalid-feedback">
                                    Please enter your Student ID.
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                <a href="login.php" class="btn btn-secondary mb-3 align-top">Back</a>
                            </div>
                        </form>
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                            $student_id = $_POST['student_id'];
                            require_once "../database/db.php";

                            // Update student status
                            $sql = "UPDATE student SET status_id = 6 WHERE student_id = ?";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("s", $student_id);

                            if ($stmt->execute()) {
                                echo '<div class="alert alert-success mt-3">The account will be unusable.</div>';
                            } else {
                                echo '<div class="alert alert-danger mt-3">Error updating status: ' . $stmt->error . '</div>';
                            }

                            // Close connections
                            $stmt->close();
                            $con->close();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
